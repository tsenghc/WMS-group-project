<?php
//啟動 session
session_start();
require_once 'dbConn.php';

// 宣告一組預設的帳號密碼
$user     = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT userId,password,token FROM Account where userId ='$user';";

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

if (isset($_POST['username'])) {
    if ($_POST['username'] == $userinfo['userId'] && $userinfo['password']==  md5(md5($password))) {
        echo 'Suess exsitUID';
        $_SESSION['userid']=$userinfo['userId'];
        
        $_SESSION['loginstatus']="succ";
        setcookie("userid",$userinfo['userId'],time()+3600*12,"/");
        
        setcookie("loginstatus",'succ',time()+3600*12,"/");
        header('Location:../main.php');
    }
    else{
        $_SESSION['loginstatus']="fall";
        echo 'error';
        header('Location:../index.php');

    }
}
else {
	echo '帳密輸入不完全';
}
/*
// 使用 isset 判斷POST中此變數是否存在
if(isset($_POST['username']) && isset($_POST['password'])) {
// 比對帳號密碼
if($_POST['username'] == $user && $_POST['password'] == $password) {
// 如果一致，顯示登入成功, 使用轉址到後台
// 將 session 的valid值設為true的值，代表以驗證成功
$_SESSION['is_login'] = true;
header('Location: index.php');
} else {

// 將 session 的valid值設為false，代表登入失敗
$_SESSION['is_login'] = false;

// 登入失敗, 使用轉址回 login.php 並且配置ㄧ個 msg 的參數，返回登入頁
header('Location: index.php?msg=登入失敗，帳號密碼錯誤');
}
} else {
// 使用轉址，返回登入頁，並秀出警告文字
header('Location: index.php?msg=無有效的帳號與密碼');
}
*/

?>