<?php
session_start();
require_once 'dbConn.php';

// 宣告一組預設的帳號密碼
$user     = $_POST['username'];
$password = md5($_POST['password']);

$sqluser = "SELECT userId,password FROM Account where userId ='$user';";
$resultid = mysqli_query($connection, $sqluser);

$datas = array();
if ($resultid) {
    if (mysqli_num_rows($resultid) > 0) {
        while ($row = mysqli_fetch_assoc($resultid)) {
            $datas[] = $row;
        }
    }
    
} else {
    echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($_SESSION['db_conn']);
}
mysqli_free_result($resultid);

if($datas[0]['userId']==$user){
    $regcheck='無法註冊';
    
    setcookie("regstatus","fall",time()+3600*12);

    echo $regcheck;
    header('Location:../index.php');
    
}
else{
    $regcheck='註冊成功';
    //$_SESSION['regstatus']="succ";
    setcookie("regstatus","succ",time()+3600*12);
    echo $regcheck;
    $sqladduser="INSERT INTO Account (userId,password,token) VALUES('$user',md5('$password'),md5('$password'));";
    $regsucc= mysqli_query($connection,$sqladduser);
    header('Location:../index.php');
    
    
}

?>