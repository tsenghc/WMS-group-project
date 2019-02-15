<?php

$prefix = ini_get('session.upload_progress.prefix');
$name   = ini_get('session.upload_progress.name');
$key    = $prefix . $name;
session_start();

if (isset($_POST['get_info'])) {
    $logo = $prefix . $_POST['logo'];
    exit(json_encode($_SESSION[$logo]));
} elseif ($_POST) {
    echo '<script>var finashed = true;</script>';
}

?>

<div id="show_info_div"></div>
<form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $name; ?>" value="test">
    <input type="file" name="file"><br>
    <input type="submit" value="提交">
    <input type="button" value="获取信息" onclick="getUploadInfo()">
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script>
var sto = null;
var progress = null;
function getUploadInfo()
{
    $.post("index.php", {"get_info": 1, "logo": "test"}, function(data)
    {
        data = eval("(" + data + ")");

        progress = parseInt(parseInt(data.bytes_processed) * 10000 / parseInt(data.content_length)) / 100 + "%";
        document.getElementById("show_info_div").innerHTML = progress;
        sto = setTimeout("getUploadInfo()", 1000);
    });
}

if (typeof(finashed) !== "undefined")
{
    document.getElementById("show_info_div").innerHTML = "100.00% (上传成功！)";
}
</script>