<?php
session_start();
//載入 db.php 檔案，讓我們可以透過它連接資料庫
require_once 'dbConn.php';
if(($_SESSION['loginstatus'])=='succ'){
// 宣告一個 $datas 陣列，準備放查詢的資料
$datas = array();

$itemId= $_POST['itemId'];
$itemInfo=$_POST['itemInfo'];
$quan = $_POST['quan'];
$placeInfo= $_POST['placeInfo'];


echo $itemId."|";
echo $placeInfo."|";
echo $quan."|";
echo $itemInfo."|";




  // 將查詢語法當成字串，記錄在$sql變數中
  $sql = "SELECT itemInfo,quan,Env.placeInfo,Item.Image FROM `Item`,`Env` WHERE itemId = '$itemId' and Item.placeId=Env.placeId";
  // echo $sql;
  
  // 用 mysqli_query 代入sql指令 ($connection 在dbConn.php中所定義)
  $query = mysqli_query($connection, $sql);
  //如果請求成功
  if ($query)
  {
    $datas = mysqli_fetch_assoc($query);

    //釋放資料庫查詢到的記憶體
    mysqli_free_result($query);
  }
  else
  {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($connection);
  }



  	// 結束mysql連線 ($connection 在dbConn.php中所定義)
  mysqli_close($connection);

}
else{
$LoginStat='show'; 
}
?>