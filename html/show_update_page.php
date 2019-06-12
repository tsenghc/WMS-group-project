<?php
$id=$_POST['id'];
require_once 'mysql.inc.php';
try {
$conn= open_db();
$sql="SELECT id,name,birth,addr FROM `student_filed1`WHERE id=?";
$stmt=$conn->prepare($sql);
$result=$stmt->execute(array($id));
if($result){
           $rows=$stmt->fetchAll();
           $msg="<form action=do_update_action.php method=post>\n";
           $row=$rows[0];
           $msg.="<input type=hidden name=id value=".$row['id']. ">";
           $msg.="姓名:"."<input type=text name=name value=".$row['name']."]>";
           $msg.="住址:"."<input type=text name=addr value=".$row['addr']."]>";
           $msg.="生日:"."<input type=text name=birth value=".$row['birth']."]>";
           $msg.=" <input type=submit value=更新>\n";
           $msg.="</form>";
       }
       else{$msg="執行錯誤";}
} catch (Exception $ex) {
     $msg= $e->getMessage(); 
}
?>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php echo $msg  ?>
    </body>
</html>