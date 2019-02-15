
<?php
//啟動 session
session_start();
if(isset($_COOKIE['token'])){
require_once 'php/dbConn.php';

$sql = "SELECT placeId,placeInfo from Env;";

$result = mysqli_query($connection, $sql);

$datas = array();
if ($result) {
    // 使用 mysqli_num_rows 方法取得資料列數，並判斷是否大於0
    // 取得的量大於0代表有資料@
   
    if (mysqli_num_rows($result) > 0) {
        
        // while迴圈會根據查詢到的資料筆數，迭代到結束為止
        while ($row = mysqli_fetch_assoc($result)) {
            $datas[] = $row;
        }
        
    }
    mysqli_free_result($result);
} else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['db_conn']);
}
if(!empty($datas)){
	foreach($datas as $key => $value){
		$userinfo=$value;
	}
}
}
else
{
  //echo "error";
}

?>
<html>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"crossorigin="anonymous"></script>
    <link rel=stylesheet type="text/css" href="css/MainMenuCss.css"><!-- ... MenuCSS檔 ... -->
    
    <script type="text/javascript" language="javascript" src="js/MainMenuJs.js"></script><!-- ... JQueryMenu動畫 ... -->

<script>
		  function delete_cookie() {
  document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "userid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  location="index.php";
  
}
		</script>

 <script>
 base64_data="";
 function readURL(input) {
    
$(function () {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //$('#falseinput').attr('src', e.target.result);
            //$('#base').val(e.target.result);
            base64_data=(e.target.result);
            //alert(base64_data);
        };
        reader.readAsDataURL(input.files[0]);
        
    }
    });
}
</script>
<script>function placeAdd() {

  //alert(document.getElementById('placeInfo').value);
	function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
var token=getCookie('token');
//alert(token);
    $(function () {  

    	$.ajaxSetup({  async:false });
                $.ajax({
               
                    url: "https://taki.dog/api/Env/add",              
                    type: "POST",
                    cache:false,
                    contentType: 'application/json',
                    dataType: 'json',
                    data:JSON.stringify(
                        {
                          placeInfo:$('#placeInfo').val(),
                          Image:base64_data,
                          token:$.cookie('token')
                        }
                            
                    ),success: function (data) {
                        alert("成功傳送資料");
                       
                        // alert(data['token']);
                        // $.cookie('token', data['token'], { path:'/', expires: 5 });        
                        // document.cookie = "name=test";
                        // location.reload();           
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            });
           alert("按下確定請等待上傳成功出現");
        
}
        </script>
</head>

<body style='font-family:Microsoft JhengHei;'>
  <div id="loginstatus" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header"  align="center">
                  <p class="modal-title" >登入狀態</p>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body btn-group-vertical">
                  <P style="color:red">登入失敗或未登入</P>
                  <button type="button" class="btn btn-info" onclick=location.href="index.php">回首頁</button>
                </div>
              </div>
            </div>
          </div>
  
  
  
    <div class="row bg-info container-fluid" style="height:75px ;padding:0px;margin:0px;">
        <div class="col-sm-2 col-4 align-self-center ">
         <?php
          if (isset($_COOKIE["userid"]))
            echo "<p style=color:block> 歡迎"  . $_COOKIE["userid"].  "</p>";
          else
            echo "<p style=color:red>登入失敗"."</p>";
          ?>
        </div>
        <div class="col-sm-2 col-4 mx-auto form-inline" sytle=align:center>
            <button type="button" class="btn btn-primary container-fluid col-8 " data-toggle="modal" data-target="#exampleModal">
              <i class="fas fa-bars"></i>
            </button>
        </div>
   
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">主選單</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body btn-group-vertical">
                  <button type="button" class="btn btn-info" onclick="javascript:location.href='main.php'">物品</button>
                  <button type="button" class="btn btn-info" onclick="javascript:location.href='locate.php'">地點</button>
                  <button type="button" class="btn btn-info" onclick="javascript:location.href='newitem.php'">新增物品</button>
                  <button type="button" class="btn btn-info" onclick="javascript:location.href='newlocate.php'">新增地點</button>
                </div>
              </div>
            </div>
          </div>
         <div class="form-inline col-sm-7 col-2"><a class="navbar-brand col-sm-1" href="main.php">
                    <img src="./img/find-my-friend.png" width="30" height="30" class="d-inline-block align-top" alt="LOGO">
                    找傢伙
                </a>
                </div>
      <button type="button" class="btn btn-info col-sm-1 col-2" onclick="delete_cookie()">登出</button>
    </div> 
			 
			  
			  <hr style="margin:5px" >
			  


<div class="content">
			<div class="container">
			  <form style="margin:20px;" action="" method="post">
			    <!-- 物品名稱 -->
			    <div class="form-group row">
            
            
            <label for="example-text-input" class="col-5 col-sm-3 col-form-label">地點名稱</label>
              <input class="form-control col-7 col-sm-7" type="text" id="placeInfo" name="placeInfo">
           
          </div>
      <!--     數量 -->
      <!--    <div class="form-group row">-->
      <!--      <label for="example-text-input" class="col-3 col-sm-2 col-form-label">溫度</label>-->
            
      <!--        <input class="form-control col-9 col-sm-8" type="text" id="Tmp" name="Tmp" >-->
            
      <!--    </div>-->
      <!--     地點 -->
      <!--    <div class="form-group row">-->
      <!--      <label for="example-text-input" class="col-3 col-sm-2 col-form-label">濕度</label>-->
            
      <!--        <input class="form-control col-9 col-sm-8" type="text" id="Hum" name="Hum" >-->
            
              
      <!--        <div class="form-group col-9 col-sm-8">-->
			   <!--       <select class="form-control ">-->
			  	<!--<?php foreach($datas as $row): ?>-->
			  	<!--<option value = <?php echo $row['placeId'];?> id = "text_place" name="placeInfo"><?php echo $row['placeInfo'];?></option>-->
			   <!-- <?php endforeach ?>-->
      <!--  			  </select>-->
      <!--    </div>-->

      <!--    </div>-->
           
          <div class="form-group row">
            <label for="example-text-input" class="col-5 col-form-label">上傳照片</label>
            
              <div class="form-group">
				<input id="fileinput" type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this);" />
              </div>
          </div>
          <!-- 圖片上傳 -->
          
          <!-- 送出取消 -->
          <div class="row">
            <div class="col-12">
              <button  class="btn btn-primary float-right" onclick=placeAdd() > 送出</button>
              <button style="margin-right: 10px;" type="button" class="btn btn float-right" onclick="location.href='main.php';">取消</button>
            </div>
          </div>
          </form>
			</div>
		</div>
</html>

