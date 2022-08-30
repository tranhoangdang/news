<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }

    $admin = $_SESSION['admin'];

    if(isset($_POST['submit'])){
        $old_password = $_POST['old-password'];
        $new_password = $_POST['new-password'];
        $re_password = $_POST['re-password'];

        $sql = "SELECT password FROM admins WHERE id = '$admin'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);

        if($row['password'] == md5($old_password)){
            if(strlen($new_password) >= 8){
                if($old_password != $new_password){
                    if($new_password == $re_password){
                        $sql = "UPDATE admins SET password = md5('$new_password') WHERE id = '$admin'";
                        if($conn->query($sql) === TRUE){
                            set_flash_session('mess_flash','Mật khẩu đã được thay đổi.');
                            redirect('change-password.php');
                        }
                        else{
                            set_flash_session('error','Không thể đổi mật khẩu.');
                            redirect('change-password.php');
                        }
                    }
                    else{
                        set_flash_session('error','Mật khẩu không trùng khớp.');
                        redirect('change-password.php');
                    }
                }
                else{
                    set_flash_session('error','Mật khẩu mới không được giống mật khẩu hiện tại.');
                    redirect('change-password.php');
                }
            }
            else{
                set_flash_session('error','Mật khẩu phải dài hơn 8 ký tự.');
                redirect('change-password.php');
            }
        }
        else{
            set_flash_session('error','Mật khẩu hiện tại không đúng.');
            redirect('change-password.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu | Quản trị viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="shortcut icon" href="../public/images/logo.png" type="image/x-icon">
</head>
<body class="ad-bg">
    <?php include('components/header.php'); ?>
    <main class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-3 sidebar">
                <?php include('components/sidebar.php'); ?>
            </div>
            <div class="col-lg-10 col-md-9">
                <article class="ad-article">
                    <h2 class="heading">Đổi mật khẩu</h2>
                    <div class="row">
                        <div class="col-lg-6 m-auto">
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
                                    <label for="old-password" class="form-label">Mật khẩu hiện tại:</label>
                                    <input type="password" class="form-control" id="old-password" name="old-password" placeholder="Nhập mật khẩu hiện tại" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new-password" class="form-label">Mật khẩu mới:</label>
                                    <input type="password" class="form-control" id="new-password" name="new-password" placeholder="Nhập mật khẩu hiện mới" required>
                                </div>
                                <div class="mb-3">
                                    <label for="re-password" class="form-label">Nhập lại mật khẩu mới:</label>
                                    <input type="password" class="form-control" id="re-password" name="re-password" placeholder="Nhập lại mật khẩu hiện mới" required>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="../public/js/main.js"></script>
</body>
</html>