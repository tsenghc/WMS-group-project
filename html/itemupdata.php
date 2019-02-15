<?php
session_start();
//載入 db.php 檔案，讓我們可以透過它連接資料庫
require_once 'php/dbConn.php';
if(($_SESSION['loginstatus'])=='succ'){
// 宣告一個 $datas 陣列，準備放查詢的資料
$datas = array();

$itemId = $_GET['id'];
$action = $_GET['action'];

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>產品編輯</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP"
            crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
        <link rel=stylesheet type="text/css" href="css/MainMenuCss.css"><!-- ... MenuCSS檔 ... -->

        <script type="text/javascript" language="javascript" src="js/MainMenuJs.js"></script><!-- ... JQueryMenu動畫 ... -->

        <script>
            function readURL(input) {
                $(function () {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            base64_data = (e.target.result);
                            alert(base64_data);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                });
            }
        </script>

        <script>
            function ItemAdd() {
                function getCookie(cname) {
                    var name = cname + "=";
                    var decodedCookie = decodeURIComponent(document.cookie);
                    var ca = decodedCookie.split(';');
                    for (var i = 0; i < ca.length; i++) {
                        var c = ca[i];
                        while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                }
                var token = getCookie('token');
                alert(token);
                $(function () {
                    $.ajaxSetup({
                        cache: false,
                        async: false
                    });
                    $.ajax({
                        url: "https://taki.dog/api/item/add",
                        type: "POST",
                        cache: false,
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify({
                                placeid: $('#text_place').val(),
                                iteminfo: $('#text_info').val(),
                                quan: $('#text_quan').val(),
                                Image: base64_data,
                                token: $.cookie("token")
                            }

                        ),
                        success: function (data) {
                            alert("成功上傳");

                            // alert(data['token']);
                            // $.cookie('token', data['token'], { path:'/', expires: 5 });        
                            // document.cookie = "name=test";
                            // location.reload();           
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                        }
                    });
                });

            }
        </script>
    </head>