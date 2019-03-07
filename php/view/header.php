<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hội thảo Khoa Học Công Nghệ 4.0</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <style>
        .thumbnail {
            position: relative;
            text-align: center;
            color: white;
        }

        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>

    <!-- <div class="jumbotron text-center" style="margin-bottom:0"> -->
    <div class="thumbnail">
        <img src="http://pace.edu.vn/vn/Uploads/ImageContent/2015/05/banner-hoithao.jpg" alt="" width="100%">
        <div class="centered">
            <h3>Hội thảo Khoa Học Công Nghệ 4.0</h3>
        </div>
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Bài báo</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
            <?php 
                // $_SESSION['username'] = 'test';
                if (!isset($_SESSION['username'])) {
                    ?>
                    <li>
                        <a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('login').style.display='block'">
                            <i class="fa fa-sign-in"></i> Đăng nhập
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('register').style.display='block'">
                            <i class="fa fa-edit"></i> Đăng ký
                        </a>
                    </li>
                <?php
                } else {
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                <i class="fa fa-user"></i> Xin chào, <?php echo htmlspecialchars($_SESSION['name']) ?>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Thông tin cá nhân</a>
                                <a class="dropdown-item" href="#">Gửi bài</a>
                            </div>
                        </li>
                        <li>
                            <a class="nav-link" href="logout.php">
                                <i class="fa fa-sign-out"></i> Đăng xuất
                            </a>
                        </li>
                <?php
                }
            ?>
            </ul>
        </div>
    </nav>

    <div id="login" class="modal">
        <!-- Modal Content -->
        <form class="modal-content animate">
            <div class="container">
                <h1>Đăng Nhập</h1>
                <p>Hãy điền thông tin tài khoản.</p>
                <hr>
                <label for="uname">
                    <b>Tên tài khoản</b>
                </label>
                <input type="text" class="form-control" placeholder="Nhập tên tài khoản" name="username" id="username" required>

                <label for="psw">
                    <b>Mật khẩu</b>
                </label>
                <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password" id="password" required>

                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                <label>
                    <input type="checkbox" checked="checked" name="remember"> Nhớ tài khoản
                </label>
            </div>
        </form>
    </div>

    <div id="register" class="modal">
        <form class="modal-content animate">
            <div class="container">
                <h1>Đăng Ký</h1>
                <p>Hãy điền thông tin tạo tài khoản.</p>
                <hr>
                <label for="uname">
                    <b>Tên tài khoản</b>
                </label>
                <input type="text" class="form-control" placeholder="Nhập tên tài khoản" name="username" id="username2" required>

                <label for="psw">
                    <b>Mật khẩu</b>
                </label>
                <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password" id="password2" required>

                <label for="psw-repeat">
                    <b>Nhập lại mật khẩu</b>
                </label>
                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="confirm_password" id="confirm_password" required>

                <div class="clearfix">
                    <button type="submit" class="btn btn-primary">Đăng Ký</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Get the modal
        var modalLogin = document.getElementById('login');
        var modalRegister = document.getElementById('register');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target === modalLogin) {
                modalLogin.style.display = "none";
            }
            if (event.target === modalRegister) {
                modalRegister.style.display = "none";
            }
        }
        
        $('#login').on('submit', function(e) {
            e.preventDefault();
            
            var username = $("#username").val();
            var password = $("#password").val();
            $.ajax({
                type     : "POST",
                url      : 'login.php',
                data     : "username="+username+"&password="+password,
                success  : function(result) {
                    if (result !== '') {
                        window.alert(result);
                    }
                    else {
                        location.reload()
                    }
                }
            });
        });
        

        $('#register').on('submit', function(e) {
            e.preventDefault();

            var username = $("#username2").val();
            var password = $("#password2").val();
            var comfirm_password = $("#confirm_password").val(); 
            $.ajax({
                type     : "POST",
                url      : 'register.php',
                data     : "username="+username+"&password="+password+"&confirm_password="+confirm_password,
                success  : function(result) {
                    if (result !== '') {
                        window.alert(result);
                    }
                    else {
                        window.alert('Đăng ký thành công!');
                        location.reload();
                    }
                }
            });
        });
    </script>
