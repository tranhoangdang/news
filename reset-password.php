<?php
    include('database/conn.php');
    require_once('functions.php');
    
    $OTP = $_GET['token'];
    $sql = "SELECT password FROM users WHERE OTP='$OTP'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        redirect('index.php');
    }

    if(isset($_POST['submit'])){
        $password = $_POST['password'];
        $re_password = $_POST['re-password'];
        if(strlen($password) >= 8){
            if($password == $re_password){
                $sql = "UPDATE users SET password=md5('$password'), OTP=NULL WHERE OTP='$OTP'";
                if($conn->query($sql) === TRUE){
                    set_flash_session('mess_flash','Mật khẩu đã được khôi phục.');
                }
                else{
                    set_flash_session('error','Không thể cập nhật mật khẩu.');
                }
                
            }
            else{
                set_flash_session('error','Mật khẩu không trùng khớp.');
            }
        }
        else{
            set_flash_session('error','Mật khẩu phải dài hơn 8 ký tự.');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu</title>
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
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <h3 class="d-flex justify-content-center">Đổi mật khẩu</h3>
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
                        <label for="password" class="form-label">Mật khẩu mới:</label>
                        <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="re-password" class="form-label">Nhập lại mật khẩu mới:</label>
                        <input type="password" class="form-control" id="re-password" placeholder="Nhập mật khẩu" name="re-password" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>