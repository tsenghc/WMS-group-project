<?php
session_start();
session_destroy();
setcookie("regstatus","",time()-3600*12);
setcookie("userid","",time()-3600*12);
setcookie("token","",time()-3600*12);
setcookie("loginstatus","",time()-3600*12);
header('Location: ../index.php');
exit;
?>
