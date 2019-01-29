import random
from flask import Flask
from flask import request
from flask import send_file
from flask_cors import CORS
from flask import abort

import json 
import SQL_function
import base64
import datetime
import qrcode
import io,os
from qrcode.image.pure import PymagingImage
from PIL import Image
from OpenSSL import SSL
import ssl

"""
Var1.3
+ add Env/Update
+ anyUpdate can remove old Image 



Var1.2
@2018.12.26
Debug_Status: False
+ file/image/small
---auto rotate image by EXIF
---auto resize and cov to JPEG


Var1.1
@2018.12.20

Debug_Status: True
in debug
- /item/add
- /Env/add

"""
app = Flask(__name__)
cors = CORS(app, resources={r"/api/account/*": {"origins": "*"}})
cors = CORS(app, resources={r"/api/item/add": {"origins": "*"}})
cors = CORS(app, resources={r"/api/items/Update": {"origins": "*"}})
cors = CORS(app, resources={r"/api/Env/add": {"origins": "*"}})
cors = CORS(app, resources={r"/api/Env/Update": {"origins": "*"}})


@app.before_request
def limit_remote_addr():
    banlist = ['114.116.26.86','221.229.196.82']
    if request.remote_addr in banlist:
        abort(403)  # Forbidden

@app.route('/api/item/add', methods=['POST'])
def itemAdd():
    if request.method == 'POST':
        global core
        #print(core.pr())
        res = {'status':False,'error':10}
        data = request.get_json()
        #data['token'] = True # for debug 
        #print(data['token'])
        
        if core.token_check(data['token'])['error'] == 0:
            #save image
            image_token = '%032x'%random.getrandbits(128)
            data['itemid'] = "items_fromIOS_"
            Field = ['itemId','itemInfo','quan','placeId','image']
            if len(data["Image"]) < 100:
                image_token = 'NoImage'

            value = [data['itemid']+(('_%32x'%random.getrandbits(128))[0:9]).replace(" ",""),data['iteminfo'],data['quan'],data['placeid'],image_token]
            re = core.Inst(Table='Item',Field=Field,Value=value)
            if re['error'] != 0:
                return json.dumps(res)
            try:
                if image_token != 'NoImage':
                    with open('image/%s.png'%image_token,'wb') as op:
                        if data['Image'][0:9] == 'data:imag':
                            data['Image'] = data['Image'][data['Image'].index('/',15):len(data['Image'])]
                        op.write(base64.decodebytes(data['Image'].encode('utf-8')))
                res['status'] = True
                res['error'] = 0
            except:
                res['error'] = 7
                return json.dumps(res)
        return json.dumps(res)
        
@app.route('/api/Env/add', methods=['POST'])
def EnvAdd():
    if request.method == 'POST':
        global core
        #print(core.pr())
        res = {'status':False,'error':10}
        data = request.get_json()
        #print(data)
        
        #data['token'] = True # for debug 
        #if True:
        if core.token_check(data['token'])['error'] == 0:
            #save image
            core.log(str(data['placeInfo'])+"  Add")
            image_token = '%032x'%random.getrandbits(128)
            data['placeId'] = "place_fromIOS_"

            Field = ['placeId','placeInfo']
            value = [data['placeId']+(('_%32x'%random.getrandbits(128))[0:9]).replace(" ",""),data['placeInfo']]
            try:
                if len(data["Image"]) > 10:
                    Field.append('Image') 
                    value.append(image_token)
                    with open('image/%s.png'%image_token,'wb') as op:
                        if data['Image'][0:9] == 'data:imag':
                            data['Image'] = data['Image'][data['Image'].index('/',15):len(data['Image'])]
                        op.write(base64.decodebytes(data['Image'].encode('utf-8')))
            except:
                pass

            re = core.Inst(Table='Env',Field=Field,Value=value)
            if re['error'] != 0:
                return json.dumps(res)
            try:
                #with open('image/%s.png'%image_token,'wb') as op:
                #    op.write(base64.decodebytes(data['Image'].encode('utf-8')))
                res['status'] = True
                res['error'] = 0
            except:
                res['error'] = 7
                return json.dumps(res)
            return json.dumps(res)

@app.route('/api/Search',methods=['POST'])
def Search():
    if request.method == 'POST':
        global core
        res = {'status':False,'error':10}
        data = request.get_json()
        #print(data)
        try:
            #print('wow')
            data = core.Search(QR_json=data)
            #print(data)
            if data['error'] == 0:
                res['status'] = True
                res['error'] = 0
                res['data'] = data
            return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)

        except:
            return json.dumps(res)

@app.route('/api/Env/get',methods=['GET'])
def place_get():
    if request.method == 'GET':
        global core
        res = {'status':False,'error':10}    
        res['data'] = core.getPlaceId()
        if res['data']['error'] == 0:
            res['error'] = 0
            res['status'] = True
            return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
        return {'error':10}

@app.route('/api/items/get/<string:itemid>',methods=['GET'])
def item_get(itemid):
    if request.method == 'GET':
        global core
        res = {'status':False,'error':10}    
        res['data'] = core.simple_item_get(itemid)
        if res['data']['error'] == 0:
            res['error'] = 0
            res['status'] = True
            return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
        return json.dumps({'error':10},ensure_ascii=False,cls=ComplexEncoder)

@app.route('/api/items/QR/<string:itemid>',methods=['GET'])
def QR_make_item(itemid):
    global core
    #print('wow')
    data = core.simple_item_get(itemid)
    #print(data)
    if data['error'] == 0 :
        #{"type":"items","text":"items_what_001","placeId":"place_sideA_001"}
        ot = {}
        ot['type'] = 'items'
        ot['text'] = data['itemid']
        ot['placeid'] = data['placeid']
        qr = qrcode.QRCode(
            version=1,
            error_correction=qrcode.constants.ERROR_CORRECT_L,
            box_size=10,
            border=1,
        )
        qr.add_data(json.dumps(ot,ensure_ascii=False,cls=ComplexEncoder))
        #qr.make(image_factory=PymagingImage)
        #mg = qrcode.make('Some data here', image_factory=PymagingImage)
        img = qr.make_image(fill_color="black", back_color="white")
        imgByteArr = io.BytesIO()
        img.save(imgByteArr, format='PNG')
        imgByteArr = imgByteArr.getvalue()
        return send_file(io.BytesIO(imgByteArr),mimetype='image/png')
        
@app.route('/api/Env/QR/<string:placeid>',methods=['GET'])
def QR_make_Env(placeid):
    global core
    if True :
        ot = {}
        ot['type'] = 'place'
        ot['placeid'] = placeid
        qr = qrcode.QRCode(
            version=1,
            error_correction=qrcode.constants.ERROR_CORRECT_L,
            box_size=10,
            border=1,
        )
        qr.add_data(json.dumps(ot,ensure_ascii=False,cls=ComplexEncoder))
        #qr.make(image_factory=PymagingImage)
        #mg = qrcode.make('Some data here', image_factory=PymagingImage)
        img = qr.make_image(fill_color="black", back_color="white")
        imgByteArr = io.BytesIO()
        img.save(imgByteArr, format='PNG')
        imgByteArr = imgByteArr.getvalue()
        return send_file(io.BytesIO(imgByteArr),mimetype='image/png')
     
@app.route('/api/items/Update',methods=['POST'])
def item_update():
    global core
    res = {'status':False,'error':10}
    try:
        data = request.get_json()
    except:
        return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
    if core.token_check(data['token'])['error'] == 0:
        image_bool = False
        old_data = core.simple_item_get(itemid=data['itemId'])
        #print(old_data)
        if old_data['error'] == 0:

            Update = {
                'itemId':data['itemId'],
                'itemInfo':data['itemInfo'],
                'quan':data['quan'],
                'placeId':data['placeId']
            }
            try:
                if len(data['Image']) > 10:
                    image_token = ('%032x'%random.getrandbits(128)).replace(" ","")
                    if len(old_data['image']) > 10:
                        os.remove("image/%s.png"%old_data['image'])
                    image_bool = True
                    Update["Image"] = image_token
            except:
                pass
            if image_bool:
                    try:
                        with open('image/%s.png'%image_token,'wb') as op:
                            if data['Image'][0:9] == 'data:imag':
                                data['Image'] = data['Image'][data['Image'].index('/',15):len(data['Image'])]
                            op.write(base64.decodebytes(data['Image'].encode('utf-8')))
                    except BaseException as ee:
                        core.log("error from  item Update image "+str(ee))
                        pass    
            
            where = {
                'itemId':data['itemId']
            }
            r = core.Update(Table='Item',dic=Update,Where=where)
            #print(r)
            if r['error'] != 0:
                core.log('Update error %s'%str(data))
                res['error'] = 9
                return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
            res['error'] = 0
        else:
            res['error'] = 484

    return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)

     
@app.route('/api/Env/Update',methods=['POST'])
def Env_update():
    global core
    res = {'status':False,'error':10}
    try:
        data = request.get_json()
    except:
        return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
    if core.token_check(data['token'])['error'] == 0:
        image_bool = False
        remove_bool = False
        #print(old_data)
        old_data = core.simple_place_get(placeid=data['placeId'])
        #print(old_data)
        if old_data['error'] == 0:

            Update = {
                'placeId':data['placeId'],
                'placeInfo':data['placeInfo']

            }
            try:
                if len(data['Image']) > 10:
                    #print("image data")
                    image_token = ('%032x'%random.getrandbits(128)).replace(" ","")
                    if len(old_data['PlaceImage']) > 10:
                        #print(old_data['PlaceImage'])
                        remove_bool = True
                        #print("remove")
                    image_bool = True
                    Update["Image"] = image_token
            except:
                pass
            try:
                if remove_bool:
                    os.remove("image/%s.png"%old_data['PlaceImage'])
            except:
                pass
            if image_bool:
                    try:
                        with open('image/%s.png'%image_token,'wb') as op:
                            if data['Image'][0:9] == 'data:imag':
                                data['Image'] = data['Image'][data['Image'].index('/',15):len(data['Image'])]
                            op.write(base64.decodebytes(data['Image'].encode('utf-8')))
                    except BaseException as ee:
                        core.log("error from  item Update image "+str(ee))
                        pass    
            
            where = {
                'placeId':data['placeId']
            }
            r = core.Update(Table='Env',dic=Update,Where=where)
            #print(r)
            if r['error'] != 0:
                core.log('Update place error %s'%str(data))
                res['error'] = 9
                return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
            res['error'] = 0
        else:
            res['error'] = 494

    return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)



@app.route('/api/image/Upload',methods=['POST'])
def image_upload():
    global core
    #print(core.pr())
    res = {'status':False,'error':10}
    data = request.get_json()
    #data['token'] = True # for debug 
    if core.token_check(data['token'])['error'] == 0:
        #save image
        #print(data.keys())
        core.log("start upload%s"%data['token'])
        core.log("base%s"%data['Image'])

        image_token = '%032x'%random.getrandbits(128)
        try:
            with open('image/%s.png'%image_token,'wb') as op:
                op.write(base64.decodebytes(data['image'].encode('utf-8')))
                #op.write(base64.decodebytes(data['image']))
            res['status'] = True
            res['image'] = image_token
            res['error'] = 0
            core.log("done upload %s"%data['token'])
        except:
            res['error'] = 7
            core.log("error upload %s"%data['token'])
            return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)
        return json.dumps(res,ensure_ascii=False,cls=ComplexEncoder)


@app.route('/api/account/login',methods=['POST'])
def account_login():
    global core
    
    data = request.get_json()
    #print(data)
    #print(len(data))
    core.log(str(request.get_data()))
    if len(data) != 2:
        return json.dumps({'error':887},ensure_ascii=False,cls=ComplexEncoder)
    try:
        login_status = core.Login(userId=data['userid'],Password=data['password'],flash_token=True)
        #print(login_status)
        if login_status['error'] == 0:
            return json.dumps(login_status,ensure_ascii=False,cls=ComplexEncoder)
    except:
        json.dumps({'error':888},ensure_ascii=False,cls=ComplexEncoder)
    
    return json.dumps({'error':890},ensure_ascii=False,cls=ComplexEncoder)


@app.route('/api/account/register',methods=['POST'])
def account_reg():
    global core
    data = request.get_json()
    #print(len(data))
    if len(data) != 2:
        return json.dumps({'error':987},ensure_ascii=False,cls=ComplexEncoder)

    try:
        login_status = core.Register(userId=data['userid'],Password=data['password'])
        if login_status['error'] == 0:
            return json.dumps(login_status,ensure_ascii=False,cls=ComplexEncoder)
    except:
        json.dumps({'error':988},ensure_ascii=False,cls=ComplexEncoder)
    
    return json.dumps({'error':990},ensure_ascii=False,cls=ComplexEncoder)


@app.route('/api/file/image/<string:image_hash>',methods=['GET'])
def image_show(image_hash):
    try:
        return send_file('image/%s.png'%image_hash,mimetype='image/png')
    except:
        return send_file('image/NoImage.png',mimetype='image/png')

@app.route('/api/file/image/',methods=['GET'])
def image_sad():
    return send_file('image/NoImage.png',mimetype='image/png')


@app.route('/api/file/image/small/<string:image_hash>',methods=['GET'])
def image_show_small(image_hash):
    if os.path.isfile('small_image/%s.png'%image_hash) == False:
        try:
            image = Image.open('image/%s.png'%image_hash)
        except:
              return send_file('image/NoImage.png',mimetype='image/png')
        width = image.width 
        height = image.height
        rate = 1.0
        if width >= 2000 or height >= 2000:
            rate = 0.3
        elif width >= 1000 or height >= 1000:
            rate = 0.5
        elif width >= 500 or height >= 500:
            rate = 0.9  
        orientation_to_rotation = {
            3: 180,
            6: 90,
            8: 270
        }
        width = int(width * rate)  
        height = int(height * rate) 
        image.thumbnail((width, height), Image.ANTIALIAS)
        try:
            exif = image._getexif() or dict()
            deg = orientation_to_rotation.get(exif.get(274, 0), 0)
            if 360-int(deg) == 270:
                image = image.transpose(Image.ROTATE_270)
            elif 360-int(deg) == 180:
                image = image.transpose(Image.ROTATE_180)
            elif 360-int(deg) == 90:
                image = image.transpose(Image.ROTATE_90)
            #image.resize( (width, height), Image.BILINEAR)
        except:
            pass
        try:
            image.save("small_image/%s.png"%image_hash,"JPEG")
        except:
            pass

    return send_file('small_image/%s.png'%image_hash,mimetype='image/png')


@app.route('/api/file/image/small/',methods=['GET'])
def image_sad_small():
    return send_file('image/NoImage.png',mimetype='image/png')




class ComplexEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, datetime.datetime):
            return obj.strftime('%Y-%m-%d %H:%M:%S')
        elif isinstance(obj, date):
            return obj.strftime('%Y-%m-%d')
        else:
            return json.JSONEncoder.default(self, obj)


if __name__ == '__main__':
    global core
    core = SQL_function.Function()

    app.run(host='127.0.0.1',port=5000)