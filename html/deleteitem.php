<?php

session_start();
//載入 db.php 檔案，讓我們可以透過它連接資料庫
require_once 'php/dbConn.php';
if(isset($_COOKIE['token'])){
// 宣告一個 $datas 陣列，準備放查詢的資料
$datas = array(); 
$itemId = $_POST['id'];
$fromUrl= $_POST['url'];
echo "delete!";
echo $fromUrl;
  // 將查詢語法當成字串，記錄在$sql變數中
  $sql = "DELETE FROM `Item` WHERE itemId = '$itemId' limit 1";
  // echo $sql;
  
  // 用 mysqli_query 代入sql指令 ($connection 在dbConn.php中所定義)
  $query = mysqli_query($connection, $sql);
  //如果請求成功
  if ($query){
  echo $itemId;
  
  header("Location:$fromUrl");
    //使用 mysqli_num_rows 方法，判別執行的語法，其取得的資料量，是否有一筆資料
    
  
    //釋放資料庫查詢到的記憶體
    
  }
  else
  {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($connection);
  }
} 
else{
echo "<script>alert('警告：你尚未登入，將跳轉至登入頁面'); location.href = 'index.php';</script>"; 
}
?>