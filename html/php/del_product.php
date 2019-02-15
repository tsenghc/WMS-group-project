<?php
require_once 'dbConn.php';

$ProdName = $_POST['ProdName'];
$ProdID = $_POST['ProdID'];
$UnitPrice = $_POST['UnitPrice'];
$Cost = $_POST['Cost'];

//宣告要回傳的結果
$result = null;

$sql = "DELETE FROM `product` WHERE `ProdID` = '{$ProdID}';";


// 用 mysqli_query 代入sql指令 ($connection 在dbConn.php中所定義)
$query = mysqli_query($connection, $sql);

//如果請求成功
if ($query)
{
  //使用 mysqli_affected_rows 判別異動的資料有幾筆，基本上只有新增一筆，所以判別是否 == 1
  if(mysqli_affected_rows($connection) == 1)
  {
    //回傳的 $result 就給 true 代表有該帳號，不可以被新增
    $result = true;
    if ($action == 'update') {
      header("Location: ../product_list.php?msg='{$ProdID} 更新成功'");
    } elseif  ($action == 'insert') {
      header("Location: ../product_list.php?msg='{$ProdID} 新增成功'");
    } 
  }
}
else
{
  echo "{$sql} 語法執行失敗，錯誤訊息：" . mysqli_error($connection);
  header("Location: ../product_list.php?msg='{$ProdID} 失敗'");
}

mysqli_close($connection);
?>