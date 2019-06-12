<?php  session_start();?>
<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"crossorigin="anonymous"> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"crossorigin="anonymous"></script>
    <link rel=stylesheet type="text/css" href="css/MainMenuCss.css"><!-- ... MenuCSS檔 ... -->
    
    <script type="text/javascript" language="javascript" src="js/MainMenuJs.js"></script><!-- ... JQueryMenu動畫 ... -->
    
    
    
       <script src="js/md5.min.js"></script>  
<script>

function gettoken() {
    var hash = md5($('#password').val()); 
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "userid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        $(function () {           
            
                    $.ajax({
                        url: "https://107db.api.nkustwork.nctu.me/api/account/login",   //存取Json的網址             
                        type: "POST",
                        cache:false,
                        contentType: 'application/json',
                        dataType: 'json',
                        data:JSON.stringify({
                            userid:$('#username').val(),
                            password:hash
                            
                        }),
                        success: function (data) {
                            
                            $.cookie('token', data['token'], { path:'/', expires: 5 });   
                            $.cookie('userid',$('#username').val(),{ path:'/', expires: 5 });
                            location="main.php";
                             
                                     
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


<body >
    <div class="row " style="padding:0px;margin:0px">
        <div class="container-fluid">
            <nav class="navbar  bg-light">
                <a class="navbar-brand col-sm-1" href="index.php">
                    <img src="./img/find-my-friend.png" width="30" height="30" class="d-inline-block align-top" alt="LOGO">
                    找傢伙
                </a>

            </nav> 
        </div>
    </div>  
    <div class="jumbotron bg-info   "style="padding:0px;margin:0px">            
                           
            <div class="container align-items-center " style="height:20%">
                        
                <img src="./img/shield.png" class="img-fluid mx-auto d-block rounded align-items-center " style="padding-top:5% " alt="Responsive image">
                        
            </div>      

            <div class="container align-items-center" style="height:70%; padding-top:3%">
                        
                <div>登入</div><br>
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control" aria-describedby="emailHelp" placeholder="Enter UserID" required pattern="^[A-Za-z0-9]+$" >
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required pattern="^[A-Za-z0-9]+$" >
                </div>
                <!--<div class="form-check">-->
                <!--    <input type="checkbox" class="form-check-input" id="Check1" >-->
                <!--    <label class="form-check-label" for="Check1">記住我</label>-->

                <!--</div>-->
                <br>
                <div class="mx-auto container-fluid">
                    <button type="login" class="btn btn-primary container-fluid"  onclick=gettoken()>登入</button>
                    <hr>
                    <button type="button" class="btn btn-primary container-fluid" data-toggle="modal" data-target="#Modal">註冊帳號</button>
                </div>
                <div class='mx-auto container-fluid'>
                <?php 
                
                
                
                    if(($_COOKIE['regstatus'])=='succ'){
                        echo '<div><p style=color:blue>註冊成功</p></div>';
                    }
                    elseif(($_COOKIE['regstatus'])=='fall'){
                        echo '<div><p style=color:red>註冊失敗</p></div>';
    
                    }
                    else
                    {
                    echo '';    
                    }
                
                if(isset($_SESSION['loginstatus'])){
                    if(($_SESSION['loginstatus'])=='succ'){
                        echo '<div><p style=color:blue>登入成功</p></div>';
                    }
                    elseif(($_SESSION['loginstatus'])=='fall'){
                        echo '<div><p style=color:red>登入失敗</p></div>';    
                    }  
                    else
                    {
                        echo '';
                    }
                    
                }
                
                ?>
                </div>
                            
            </div>
                
                
            
            

    </div>
            

            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">使用者註冊</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <form class="container-fluid "  action="./php/register.php" method="post">
                            <div class="modal-body">

                                <div class="form-group">
                                    <input type="text" name="username" class="form-control" aria-describedby="emailHelp" placeholder="Enter UserID">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                            <br>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-primary">註冊</button>
                                
                            </div>
                        </form>
                       

                            
                    </div>

                    
                </div>
            </div>
            

        </div>
    </div>
</body>

</html>