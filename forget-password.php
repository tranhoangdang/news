<?php
    include('database/conn.php');
    require_once('functions.php');
    require_once('mail.php');

    if(isset($_COOKIE['news'])){
        redirect('index.php');
    }

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $token = md5('$email').rand(10,9999);
        $sql = "SELECT email FROM users WHERE email='$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "UPDATE users SET OTP='$token' WHERE email='$email'";
            if($conn->query($sql) === TRUE){
                $subject = 'Khôi phục mật khẩu';
                $link = "<a href='localhost/news/reset-password.php?token=".$token."'>đường dẫn này</a>";
                $body = 'Hãy nhấn vào '.$link.' để khôi phục mật khẩu cho tài khoản của bạn.';
                $purpose = 2;
                SendMail($email,$subject,$body,$purpose);
            }
            else{
                set_flash_session('error','Không thể gửi mã khôi phục đến email này.');
                
            }
        }
        else{
            set_flash_session('error','Email chưa được đăng ký.');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="shortcut icon" href="public/images/logo.png" type="image/x-icon">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include('components/header.php'); ?>
    <div class="border-bottom"></div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 m-auto">
                <h2 class="d-flex justify-content-center heading">Quên mật khẩu</h2>
                <?php
                    $mess = get_flash_session('mess_flash');
                    if(isset($mess)){
                ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Thành công!</strong> <?= $mess ?>
                </div>
                <?php        
                    }
                ?>
                <?php
                    $error = get_flash_session('error');
                    if(isset($error)){
                ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Thất bại!</strong> <?= $error ?>
                </div>
                <?php        
                    }
                ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Nhập địa chỉ email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Nhập email" name="email">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Tiếp tục</button>
                </form>
            </div>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>