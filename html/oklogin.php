
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    
</head>


<body >
    <div class="row " style="padding:0px;margin:0px">
        <div class="container-fluid" >
            <nav class="navbar  bg-light">
                <a class="navbar-brand col-sm-1" href="index.php">
                    <img src="./img/find-my-friend.png" width="30" height="30" class="d-inline-block align-top" alt="LOGO">
                    找傢伙
                </a>

            </nav> 
        </div>
    </div>  
        <div class="row bg-info  "style="padding:0px;margin:0px">            
            <div class="container-fluid " style="height:93%">
                <div class="mx-auto ">
                    <div class="col-sm-10 col-md-10 col-12 col-lg-6 mx-auto" style=" height:30%">
                        <div class="modal-dialog-centered" >
                            <img src="./img/shield.png" class="img-fluid mx-auto d-block rounded " alt="Responsive image">
                        </div>
                    </div>
                </div>
                <div class="col-auto mr-auto">
                        <div class="col-sm-10 col-md-10 col-12 col-lg-6 mx-auto">
                            <form action="./php/checkUser.php" method="post">
                                <div>登入</div><br>
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control" aria-describedby="emailHelp" placeholder="Enter UserID" required pattern="^[A-Za-z0-9]+$" >
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password" required pattern="^[A-Za-z0-9]+$">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Check1" >
                                    <label class="form-check-label" for="Check1">記住我</label>

                                </div>
                                <br>
                                <div class="mx-auto container-fluid">
                                    <button type="submit" class="btn btn-primary container-fluid">登入</button>
                                    <hr>
                                    <button type="button" class="btn btn-primary container-fluid" data-toggle="modal" data-target="#Modal">註冊帳號</button>
                                </div>
                                <div class='mx-auto container-fluid'>
                                <?php 
                                
                                session_start();
                                
                                if(isset($_SESSION['regstatus'])){
                                    if(($_SESSION['regstatus'])=='succ'){
                                        echo '<div><p style=color:blue>註冊成功</p></div>';
                                    }
                                    elseif(($_SESSION['regstatus'])=='fall'){
                                        echo '<div><p style=color:red>註冊失敗</p></div>';
    
                                    }
                                }
                                if(isset($_SESSION['loginstatus'])){
                                    if(($_SESSION['loginstatus'])=='succ'){
                                        echo '<div><p style=color:blue>登入成功</p></div>';
                                    }
                                    elseif(($_SESSION['loginstatus'])=='fall'){
                                        echo '<div><p style=color:red>登入失敗</p></div>';    
                                    }                                   
                                }
                                
                                                                        
                                ?>
                                </div>
                            </form>
                        </div>
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
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

</html>