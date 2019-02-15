<?php
 // 在http response中的header加入charset=utf8
  header("Content-Type:text/html; charset=utf8");
  
  // 先設定資料庫的連結資訊，如果是在本機端可以用localhost 或是127.0.0.1
  // 若資料庫跟Apache沒有安裝在同一台主機，這個參數就要設定安裝資料庫電腦的IP (例如 192.168.x.x, 或是 140.113.74.x)
  $host = "35.221.240.193"; //
  
  // 這參數請使用你database的username名稱
  $user = "tseng";         
  
  // 這參數請使用你database username的密碼
  $pass = "4311";                   
  
  // 登入後要使用的資料庫名稱 (請先將練習用資料庫 mmisdb先匯入至MySQL中)
  $db = "Project_DB";
  
  // MySQL 預設的TCP Port是3306，除非你有更改其他設定，不然就是使用預設值
  $port = 3306;
  
  // 呼叫 mysqli_connect 與資料庫連結
  // 連結成功會返回一個connection的物件
  $connection = mysqli_connect($host, $user, $pass, $db, $port);
  
  
  mysqli_query($connection, "SET NAMES utf8");
  if ($connection) {
    // 若傳回正值，就代表已經連線
    //echo "已正確連線";
  } else {
    // 否則就代表連線失敗 mysqli_connect_error() 顯示連線錯誤訊息
    echo '無法連線mysql資料庫 :<br/>' . mysqli_connect_error();
  }
?>
