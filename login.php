<?php
    include('database/conn.php');
    require_once('functions.php');

    if(isset($_COOKIE['news'])){
        redirect('index.php');
    }

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sql = "SELECT id,password,is_active FROM users WHERE email='$email'";
        $result = $conn->query($sql);
        if($result->num_rows == 1){
            $row = mysqli_fetch_assoc($result);
            if($row['is_active'] == 1){
                if($row['password'] == md5($password)){

                    setcookie("news", $row['id'], time() + (86400*30));

                    redirect('index.php');
                }
                else{
                    set_flash_session('error','Mật khẩu không đúng.');
                }
            }
            elseif($row['is_active'] == -1){
                set_flash_session('error','Tài khoản này đã bị khóa.');
            }
            else{
                set_flash_session('error','Tài khoản chưa được xác nhận. Vui lòng kiểm tra email để thực hiện xác nhận tài khoản.');
            }
        }
        else{
            set_flash_session('error','Tài khoản này chưa được đăng ký.');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
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
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <h2 class="d-flex justify-content-center heading">Đăng nhập</h2>
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
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="example@gmail.com" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu" name="password" required>
                    </div>
                    <div class="mb-3">
                        <a href="forget-password.php" class="d-flex justify-content-end">Quên mật khẩu</a>
                    </div>
                    <div class="d-flex justify-content-end">
                        <p>Bạn chưa có tài khoản?</p>
                        <a href="register.php" class="ms-2"> Tạo tài khoản</a>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>