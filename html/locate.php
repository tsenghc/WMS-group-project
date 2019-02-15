<?php
session_start();
require_once 'php/dbConn.php';
// 宣告一個 $datas 陣列，準備放查詢的資料
if(isset($_COOKIE['token'])){
      $datas = array();
      $datasP = array();
      $LoginStat='hide';
      $placeInfo = $_GET['search'];
      $placeId=$_GET['id'];
      $PN=$_GET['PN'];
      $PageS=0;//起始資料
      $PageE=10;//取多少筆資料
      // 將查詢語法當成字串，記錄在$sql變數中
      // $sql = "SELECT * FROM `product`;";
      $sql = '';
      if($PN==1||$PN==0){
        $PageS=0;//初始化頁碼
        $PN=1;
      }
      else{
        $PageS=($PN-1)*10;//1 以上頁碼改變起始資料index
      
      }
      $sqlP="pl=aceInfo,Tmp,Hum,Image,UpdateTime FROM `Env` where placeInfo like '%$placeInfo%' limit 1";
      if ($placeInfo){//如果有搜尋參數傳入，則
        $sql = "SELECT itemId,itemInfo,quan,Env.placeInfo,Item.Image,Item.UpdateTime FROM `Item`,`Env` where Env.placeId=Item.placeId and Item.placeId = '$placeId' limit $PageS,10";
        $sqlC = "SELECT * FROM `Env` WHERE placeInfo like '%$placeInfo%'";//計算資料總搜尋資料筆數
        $sqlP = "SELECT placeInfo,placeId,Tmp,Hum,Image,UpdateTime FROM `Env` where placeId = '$placeId' limit 1";//地點本身的資料
      }
      else
      {
        $sql = "SELECT itemId,itemInfo,quan,Env.placeInfo,Item.Image,Item.UpdateTime FROM `Item`,`Env` where Env.placeId=Item.placeId and Item.placeId = '$placeId' limit $PageS,10";
        $sqlC = "SELECT * FROM `Env`";//計算未搜尋資料筆數
        $sqlP =  "SELECT placeInfo,placeId,Tmp,Hum,Image,UpdateTime FROM `Env` where placeId = '$placeId' limit 1";//地點本身的資料
      }
      // 用 mysqli_query 代入sql指令 ($_SESSION['db_conn'] 在dbConn.php中所定義)
      $result = mysqli_query($connection, $sql);
      $result2 = mysqli_query($connection, $sqlC);//載入計算資料筆數的sql查詢
      $resultP = mysqli_query($connection, $sqlP);
      
      $rowcount=mysqli_num_rows($result2);//統計查詢到資料筆數
      //如果請求成功
      if ($result) {
          // 使用 mysqli_num_rows 方法取得資料列數，並判斷是否大於0
          // 取得的量大於0代表有資料@
          if (mysqli_num_rows($result) > 0) {
              // while迴圈會根據查詢到的資料筆數，迭代到結束為止
              while ($row = mysqli_fetch_assoc($result)) {
                  $datas[] = $row;
                  
              }
          }
          // 釋放資料庫查詢到的記憶體
          mysqli_free_result($result);
      } else {
          echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['db_conn']);
      }
      if ($resultP) {
          // 使用 mysqli_num_rows 方法取得資料列數，並判斷是否大於0
          // 取得的量大於0代表有資料@
          if (mysqli_num_rows($resultP) > 0) {
            
              // while迴圈會根據查詢到的資料筆數，迭代到結束為止
              while ($rowP = mysqli_fetch_assoc($resultP)) {
                  $datasP[] = $rowP;
                 
              }
          }
          else{
            
          }
          // 釋放資料庫查詢到的記憶體
          mysqli_free_result($resultP);
      } else {
          echo "{$sqlP} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['db_conn']);
      }


$placesql = "SELECT placeId,placeInfo from Env;";

$placeresult = mysqli_query($connection, $placesql);

$localdatas = array();
if ($placeresult) {
    // 使用 mysqli_num_rows 方法取得資料列數，並判斷是否大於0
    // 取得的量大於0代表有資料@
   
    if (mysqli_num_rows($placeresult) > 0) {
        
        // while迴圈會根據查詢到的資料筆數，迭代到結束為止
        while ($row = mysqli_fetch_assoc($placeresult)) {
            $localdatas[] = $row;
        }
        
    }
    
    mysqli_free_result($placeresult);
} 
else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['db_conn']);
}
}
else{
$LoginStat='show'; 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel=stylesheet type="text/css" href="css/MainMenuCss.css"><!-- ... MenuCSS檔 ... -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>物品搜尋</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" language="javascript" src="js/MainMenuJs.js"></script><!-- ... JQueryMenu動畫 ... -->
    <script>$(function dialog() { $('#loginstatus').modal(<?php echo "'".$LoginStat."'" ?>)});</script>  
    <style>
			img {
				transition : -webkit-transform 0.5s;/*延迟效果*/
			}
			img:hover {
				transform: scale(3.5,3.5);/*放大的倍数*/
			}	
		</style>
		<script>
		  function delete_cookie() {
  document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "userid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  location="index.php";
  
}
		</script>
</head>

<body style='font-family:Microsoft JhengHei;'>
  <!-- ... 未登入alert ... -->
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
  <!-- ... 未登入alert ... -->
  <!-- ... 刪除dialog ... -->
  <div class="modal fade" id="DeleteModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle" style="color:Red;">警告</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        確定要刪除嗎？此動作將無法復原!
                      </div>
                      <form action=deleteitem.php method="post">
                        <input type="hidden" id="DeleteId" name="id">
                        <input type="hidden" id="fromUrl" name="url">
                      
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">確定</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
  <!-- ... 刪除dialog ... -->
  <!-- ... 刪除javascript ... -->
  <script type="text/javascript">
            function ShowDialog(ItemId){
              
              $('#DeleteModel').modal('show');
              document.getElementById("DeleteId").value=ItemId;
              document.getElementById("fromUrl").value="locate.php";
            }
  </script>
  <!-- ... 刪除javascript ... -->         
  <!-- ... 登入成功 ... -->
    <div class="row bg-info container-fluid" style="height:75px ;padding:0px;margin:0px;">
        <div class="col-sm-2 col-3 align-self-center ">
         <?php
          if (isset($_COOKIE["userid"]))
            echo "<p style=color:block>歡迎"  . $_COOKIE["userid"].  "</p>";
          else
            echo "<p style=color:red>登入失敗"."</p>";
          ?>
        </div>
        <!-- ... 登入成功 ... -->
        <div class="col-sm-2 col-3 mx-auto form-inline" sytle=align:center>
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
          <div class="form-inline col-sm-7 col-4"><a class="navbar-brand col-sm-1" href="main.php">
                    <img src="./img/find-my-friend.png" width="30" height="30" class="d-inline-block align-top" alt="LOGO">
                    找傢伙
                </a>
                </div>
      <button type="button" class="btn btn-info col-sm-1 col-2" onclick="delete_cookie()">登出</button>
    </div> 
			 
			  
			  <hr style="margin:5px" >
			  
			  <div class="content">
			<div class="container">
			   <div class="form-group row">
			    <select class="form-control col-md-3 col-sm-9 col-9" onchange="submitForm();" id="PlaceSelected">
			        <option value="--選擇地點--" SELECTED  disabled>--選擇地點--</option>    
			      	<?php foreach($localdatas as $row): ?>
		    	  	<option value = <?php echo $row['placeId'];?> id = "text_place" name="placeInfo"><?php echo $row['placeInfo'];?></option>
		    	    <?php endforeach ?>
			    </select>
			    
			      <form action="locateedit.php" method="post">
			        <p class="col-md-1 col-sm-1 col-1" data-placement='top' data-toggle='tooltip' title='Edit' style="margin:0px">
			        <button type="submit"class='btn btn-primary btn-xs' data-title='Edit' >
        	    	          <span class='fas fa-pen-square'></span>
        	    </button>
			        <input type="hidden" name="id" value=<?php echo $datasP[0]['placeId']; ?>>
			        </p>
			      </form>
        	    	        
        	    	  
          <table class="table table-striped table-bordered col-md-7 col-sm-12 col-12 " width="100%" border=3 >
            <tr align="center">
                <!--定義 table 表格-->
                <th>地點</th>
                <th>溫度</th> 
                <th>濕度</th>
                <th>照片</th>
                <th>修改時間</th>
              </tr>
              <td><?php echo  $datasP[0]['placeInfo']; ?></td>
              <td><?php echo $datasP[0]['Tmp']; ?></td>
              <td><?php echo $datasP[0]['Hum']; ?></td>
              <td><a href="http://35.221.196.173/api/file/image/<?php echo $datasP[0]['Image']; ?>" target="_blank" ><img src="http://35.221.196.173/api/file/image/small/<?php echo $datasP[0]['Image']; ?>" style="height:30px;" alt="imgUrlNull"></a></td>
              <td><?php echo $datasP[0]['UpdateTime']; ?></td>
          </table>
        	</div>
        			  </div>
        			  </div>
			<script>
			    function submitForm(){
                   
                    window.location = "locate.php?id="+ document.getElementById("PlaceSelected").value;
                }
			</script>  
		     
		    <!--表格-->
		    <div class=" container-fluid" style="padding-left:30px;padding-right:30px">
				  <table class="table table-striped table-bordered" width="100%" border=3 >
				    <thead>
				      <tr align="center">
                <!--定義 table 表格-->
                <th>物品名稱</th>
                <th>數量</th> 
                <th>地點</th>
                <th>照片</th>
                <th>修改時間</th>
                <th>修改</th>
                <th>刪除</th>
              </tr>
				    </thead>
				    
				    <tbody>
				      <?php if (!empty($datas)): ?>
        	    	<?php foreach ($datas as $row): ?>
        	    	  <tr height="5%" style="vertical-align: middle">
        	    	    
        	    	     <td style="vertical-align: middle;"><?php echo $row['itemInfo']; ?></td>
        	    	    <td style="vertical-align: middle;" align="center"><?php echo (int)$row['quan']; ?></td>
        	    	    <td style="vertical-align: middle;"><?php echo $row['placeInfo']; ?></td>
        	    	    <td style="vertical-align: middle;" align="center"><a href="http://35.221.196.173/api/file/image/<?php echo $row['Image']; ?>" target="_blank" ><img src="http://35.221.196.173/api/file/image/small/<?php echo $row['Image']; ?>" style="height:45px;" alt="imgUrlNull"></a></td>
        	    	    <td style="vertical-align: middle;"><?php echo $row['UpdateTime']; ?></td>
        	    	    <!--修改按鈕-->
        	    	     <td width="5%" align="center" style="padding:10px;margin:10px;vertical-align:middle">
        	    	      <form action="itemedit.php" method="post">
        	    	      <p data-placement='top' data-toggle='tooltip' title='Edit' style="margin:0px">
        	    	        
        	    	          <button class='btn btn-primary btn-xs' data-title='Edit' type="submit" value=<?php echo $row['itemId'] ?> name="id">
        	    	          <span class='fas fa-pen-square'></span>
        	    	          </button>
        	    	        
        	    	      </p>
        	    	      </form>
        	    	    <!--刪除按鈕-->
        	    	    <td width="5%" align="center"  style="padding:10px;vertical-align: middle;">
        	    	      <p data-placement='top' data-toggle='tooltip' title='Delete' style="margin:0px">
        	    	        <button class="btn btn-danger" onclick="ShowDialog('<?php echo $row['itemId']; ?>')"  >
        	    	          <span class="fa fa-trash"></span>
        	    	        </button>
        	    	      </p>
        	    	    </td>
        	    	  </tr>
        	    	<?php  endforeach; ?>
        	    <?php else: ?>
  							<h3 class="text-center">無資訊</h3>
  				    <?php endif; ?>
				    </tbody>
          </table>
          
          <!-- 刪除Dialog -->
          <div class="modal fade" id="DeleteModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLongTitle" style="color:Red;">警告</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        確定要刪除嗎？此動作將無法復原!
                      </div>
                      <form action=deleteitem.php method="post">
                        <input type="" id="DeleteId" name="id">
                        
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">確定</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
          
          
          
          
          
          <div align="center">
            <?php
              $PageN=ceil($rowcount/10);
              for($i=1;$i<=$PageN;$i++){
                if($PN==$i){
                  echo  '['.$i.']';
                }
                else{
                   echo  '<a href="'.$_SERVER['PHP_SELF'].'?search='.$itemName.'&PN='.$i.'">['.$i.']</a>';
                }
               
              }
              
            ?>
          </div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>


</html>