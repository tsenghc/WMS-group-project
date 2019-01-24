## Restful & IOS app  on group project @ DB class 

### 整個專案全貌

---

可以透過網頁或是專用的IOS app，尋找物品/新增物品/透過QR 尋找物品等等

類似市場上有的倉管服務

---



為了期末小組專案，做了一些擴充功能

整個專案如下

php 接入 SQL

前端html (php ?) 部分使用Restful api



Restful server 接 SQL

IOS 基本上只是把Restful的部分功能可視化



## Restful server  (Flask)

1. 簡單註冊登入
2. 查詢/新增物品
3. 修改物品 (e.g. 數量)
4. QR code 產生
5. 自動縮小圖片以及透過EXIF旋轉圖片並輸出
6. 圖片伺服器



### IOS app

---

Swift 4

沒什麼特點，就是IOS

順暢的table view / 拍照/ 照片處理 /QR 掃描/

例外寫好寫滿，不crash，不死回圈不耗電



困難/麻煩的部分都透過第三方套件完成

token 儲存 / 圖片 自動緩存 / Alamofire   / Json 處理



### Simple demo

---

more demo : [](https://youtu.be/X8byGabslxY)

---



Scan QR to search item or place.

![](https://github.com/takidog/WMS-group-project/blob/master/demo_image/QR.gif?raw=true)

---

Search by place name

![](https://github.com/takidog/WMS-group-project/blob/master/demo_image/search_by_place.gif?raw=true)



---

Search by text

![](https://github.com/takidog/WMS-group-project/blob/master/demo_image/search_by_text.gif?raw=true)





---

Restful/IOS 我製作的部分

整個專案包含我，共三位製作



已知缺點: restful 因沒有要跟人協作， 我不是註解派的，命名些微糟

code 不乾淨請見諒 ， flask 用 global 好像不是好方法，應該用__init.py 才是

token應該要從cookie帶才對，這裡放在資料中

