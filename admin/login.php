<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(isset($_SESSION['admin'])){
        redirect('index.php');
    }

    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT id,password FROM admins WHERE email = '$email' AND is_active = 1 ";

        $result = $conn->query($sql);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_array($result);
            if($row['password'] == md5($password)){
                $_SESSION['admin'] = $row['id'];
                redirect('index.php');
            }
            else{
                set_flash_session('error','Mật khẩu không đúng.');
            }
        }
        else {
            set_flash_session('error','Email này không tồn tại.');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Quản trị viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="shortcut icon" href="../public/images/logo.png" type="image/x-icon">
</head>
<body class="ad-bg">
    <h2 class="heading">Đăng Nhập</h2>
    <div class="col-md-8 m-auto px-3">
        <form id="ad-login-form" method="POST">
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
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Nhập email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu:</label>
                <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="login">Đăng nhập</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="../public/js/main.js"></script>
</body>
</html>