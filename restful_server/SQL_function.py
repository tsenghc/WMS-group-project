import MySQLdb
import time
import hashlib
import random
import json 
import datetime
import qrcode

class Function:
    def __init__(self):
        pass
        self.IP = ''
        self.Account = ''
        self.Password = ''
        self.DateBase = ''
    def log(self,text):
        with open('wow.log','a') as e:
            e.write("[ %s ] : %s\n"%(time.strftime("%Y-%m-%d %H:%M:%S"),text))

    def raw_Input(self,SQL_text):
        db = MySQLdb.connect(self.IP, self.Account, self.Password, self.DateBase, charset='utf8' )
        cursor = db.cursor()
        cursor.execute(SQL_text)
        db.commit()
        db.close()

    def raw_Search(self,SQL_text):
        db = MySQLdb.connect(self.IP, self.Account, self.Password, self.DateBase, charset='utf8' )
        cursor = db.cursor()
        cursor.execute(SQL_text)
        data = cursor.fetchall()

        #db.commit()
        db.close()
        return data

    def Inst(self,Table,Field=[],Value=[]):
        "QQQQ"
        if len(Field) != len(Value):
            return {'error':101}
        else:
            Field = str(Field)[1:len(str(Field))-1]
            Value = str(Value)[1:len(str(Value))-1]
            db = MySQLdb.connect(self.IP, self.Account, self.Password, self.DateBase, charset='utf8' )
            cursor = db.cursor()
            SQL_text = "INSERT INTO `%s`.`%s` (%s) VALUES (%s);"%(self.DateBase,Table,Field.replace("'",""),Value)
            #cursor.execute(SQL_text)
            
            try:
                cursor.execute(SQL_text)
                db.commit()
            except:
                self.log(text="error insert : "+Field+" "+Value)
                #print(SQL_text)
                db.rollback()
                db.close()
                return {'error':102}
            #data = cursor.fetchone()    for single line 
            # data = cursor.fetchall()  for all data 
            db.close()
        return {'error':0}
    def Update(self,Table,dic={},Where={}):
        #UPDATE `Project_DB`.`Env` SET `Tmp`='30.4565', `Hum`='05656' WHERE  `placeId`='test_sideA_001' LIMIT 1;
        db = MySQLdb.connect(self.IP, self.Account, self.Password, self.DateBase, charset='utf8' )
        cursor = db.cursor()
        Where_key = list(Where.keys())[0]
        Where_val = list(Where.values())[0]
        for key,val in dic.items():
            try:
                SQL_text = "UPDATE `%s`.`%s` SET `%s`='%s' WHERE `%s`='%s' LIMIT 1;"%(self.DateBase,Table,key,val,Where_key,Where_val)
                #print(SQL_text)
                cursor.execute(SQL_text)
            except:
                self.log(text="error update : "+key+" "+val)
                db.close()
                return {'error':201}
        try:
            db.commit()
        except:
            self.log('error commit in update')
            db.rollback()
            db.close()
            return {'error':202}
        db.close()
        return {'error':0}

    def Login(self,userId,Password,flash_token=False):
        "userId is MD5    Password input MD5  check MD5( MD5() )"
        if len(Password) != 32:
            return {'error':999}
        m = hashlib.md5()
        m.update(Password.encode("utf-8"))
        Password = m.hexdigest()
        SQL_text = "SELECT IF((SELECT `userId` FROM `Project_DB`.`Account` WHERE  `userId`='%s') = '%s', True, FALSE)"%(userId,userId)
        data = self.raw_Search(SQL_text)  # Check user is sign up
        if list(data)[0][0] == 1:
            SQL_text = "SELECT IF((SELECT `password` FROM `Project_DB`.`Account` WHERE  `userId`='%s') = '%s', True, False),(SELECT `token` FROM `Project_DB`.`Account` WHERE  `userId`='%s')"%(userId,Password,userId)
            #print(SQL_text)
            data = self.raw_Search(SQL_text)
            if list(data)[0][0] == 1:
                self.log("login done Account : "+userId)
                # update new token 
                if flash_token:
                    token = '%032x'%random.getrandbits(128)
                    self.Update(Table='Account',dic={'token':token},Where={'token':list(data)[0][1]})
                    return {'error':0,'token':token}
                return {'error':0}

        return {'error':301}

    def Register(self,userId,Password):
        if len(Password) != 32:
            return {'error':999}  
        if self.Login(userId=userId,Password=Password)['error'] == 0 :  #Account alive  without Register 
            return {'error':303}
        SQL_text = "SELECT IF((SELECT `userId` FROM `Project_DB`.`Account` WHERE  `userId`='%s') = '%s', True, FALSE)"%(userId,userId)
        if list(self.raw_Search(SQL_text))[0][0] == 1:  # Check user is sign up
            return {'error':304}  # maybe it forget password QQ

        token = '%032x'%random.getrandbits(128)
        SQL_text = "INSERT INTO `Project_DB`.`Account` (`userId`, `password`, `token`) VALUES ('%s', MD5('%s'), '%s');"%(userId,Password,token)
        self.raw_Input(SQL_text=SQL_text)
        self.log("Register done Account : "+userId)
        return {'error':0,'token':token}

    def token_check(self,token):
        #print(len(token))
        if len(token) == 32:
            SQL_text="SELECT IF((SELECT COUNT(*) FROM `Project_DB`.`Account`WHERE `token`='%s')=1 ,TRUE,false);"%token   
            data = self.raw_Search(SQL_text)
            #print(data,data[0][0])
            if data[0][0] == 1:
            
                return {'error':0}
        return {'error':310}


    def Search(self,QR_json):
        try:
            QR_json = QR_json
        except:
            self.log('Search error  text : %s'%QR_json)

        'Check QR_json field not Null'
        try:
            if QR_json['type'] != '':
                pass
        except:
            return {'error':401}
        'Check OK'

        if QR_json['type'] == 'items':
            SQL_text = "SELECT * from `Project_DB`.`Item`,`Project_DB`.`Env` Where `Project_DB`.`Item`.`itemid` = '%s'AND   `Project_DB`.`Env`.`placeid`  = '%s' Limit 1"%(QR_json['text'],QR_json['placeId'])
            #print(SQL_text)
            data = self.raw_Search(SQL_text=SQL_text)
            #print(data)
            Field = ('itemid','iteminfo','Quan','placeid','image','ItemCreateTime','ItemUpdateTime','placeid','placeinfo','Tmp','Hum','PlaceImage','PlaceUpdateTime','PlaceCreateTime')
            res = {}
            # print(len(data(0)))
            # print(len(Field))
            if len(data[0]) != len(Field):
                return {'error':402}
            for i in range(len(data[0])):
                res[Field[i]] = data[0][i]
            res['error'] = 0
            res['type'] = 'items'
            #print(res) 
            #print(json.dumps(res,ensure_ascii=False,cls=ComplexEncoder))  
            return res
        
        if QR_json['type'] == 'place':
            SQL_text = """SELECT * 
            FROM `Project_DB`.`Item`,`Project_DB`.`Env`
            WHERE `Project_DB`.`Item`.`placeId` = '%s'
            AND `Project_DB`.`Item`.`placeId`=`Project_DB`.`Env`.`placeId`"""%QR_json['placeId']
            data = self.raw_Search(SQL_text=SQL_text)
            #Field = ('itemid','iteminfo','Quan','placeid','image','ItemUpdateTime','ItemCreateTime','placeid','placeinfo','Tmp','Hum','PlaceUpdateTime','PlaceCreateTime')
            Field = ('itemid','iteminfo','Quan','placeid','image','ItemCreateTime','ItemUpdateTime','placeid','placeinfo','Tmp','Hum','PlaceImage','PlaceUpdateTime','PlaceCreateTime')            
            res = {}
            res["data"] = {}
            #print()
            for i in range(len(data)):
                tmp = {}
                for x in range(len(data[i])):
                    tmp[Field[x]] = data[i][x]
                res["data"][i] = tmp
            res['error'] = 0
            res['type'] = 'place'
            #print(res) 
            #print(json.dumps(res,ensure_ascii=False,cls=ComplexEncoder))  
            return res
        if QR_json['type'] == 'bytext':
            SQL_text = """SELECT * 
            from `Project_DB`.`Item`,`Project_DB`.`Env`
            Where `Project_DB`.`Item`.`itemInfo` like '%%%s%%' And `Project_DB`.`Item`.`placeId` = `Project_DB`.`Env`.`placeId`"""%QR_json['text']
            #print(SQL_text)
            data = self.raw_Search(SQL_text=SQL_text)
            #Field = ('itemid','iteminfo','Quan','placeid','image','ItemUpdateTime','ItemCreateTime','placeid','placeinfo','Tmp','Hum','PlaceUpdateTime','PlaceCreateTime')
            Field = ('itemid','iteminfo','Quan','placeid','image','ItemCreateTime','ItemUpdateTime','placeid','placeinfo','Tmp','Hum','PlaceImage','PlaceUpdateTime','PlaceCreateTime')            
            res = {}
            res["data"] = {}
            #print()
            for i in range(len(data)):
                tmp = {}
                for x in range(len(data[i])):
                    tmp[Field[x]] = data[i][x]
                res["data"][i] = tmp
            res['error'] = 0
            res['type'] = 'place'
            #print(res) 
            #print(json.dumps(res,ensure_ascii=False,cls=ComplexEncoder))  
            return res

        return {'error':404}

    def simple_item_get(self,itemid):
        SQL_text = "SELECT * from `Project_DB`.`Item`,`Project_DB`.`Env` Where `Project_DB`.`Item`.`itemid` = '%s' Limit 1"%(itemid.replace(" ",""))

        data = self.raw_Search(SQL_text=SQL_text)

        Field = ('itemid','iteminfo','Quan','placeid','image','ItemUpdateTime','ItemCreateTime','placeid','placeinfo','Tmp','Hum','PlaceImage','PlaceUpdateTime','PlaceCreateTime')
        res = {}

        if len(data[0]) != len(Field):
            return {'error':402}
        for i in range(len(data[0])):
            res[Field[i]] = data[0][i]
        res['error'] = 0
        res['type'] = 'items'
        return res

    def simple_place_get(self,placeid):
        SQL_text = "SELECT * from `Project_DB`.`Env` Where `Project_DB`.`Env`.`placeId` = '%s' Limit 1"%(placeid.replace(" ",""))
        #print(SQL_text)
        data = self.raw_Search(SQL_text=SQL_text)
        #print(data)
        Field = ('placeid','placeinfo','Tmp','Hum','PlaceImage','PlaceUpdateTime','PlaceCreateTime')
        res = {}

        if len(data[0]) != len(Field):
            return {'error':402}
        for i in range(len(data[0])):
            res[Field[i]] = data[0][i]
        res['error'] = 0
        res['type'] = 'place'
        return res

    def getPlaceId(self):
        SQL_text = "SELECT `placeId`,`placeinfo`FROM `Project_DB`.`Env`;"
        data = self.raw_Search(SQL_text=SQL_text)
        #print(data)
        res = {}
        res['place'] = {}
        ls = []
        for i in data:
            res['place'][i[0]] = i[1]
        res['error'] = 0

        return res

    def mutilUpdate(self,jsd):
        pass
        'very hard ... in here'



class ComplexEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, datetime.datetime):
            return obj.strftime('%Y-%m-%d %H:%M:%S')
        elif isinstance(obj, date):
            return obj.strftime('%Y-%m-%d')
        else:
            return json.JSONEncoder.default(self, obj)

if __name__ == "__main__":
    core = Function()