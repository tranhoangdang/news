<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }

    if(isset($_POST['submit'])){
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $DOB = $_POST['DOB'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];
        $address = $_POST['address'];
        $position = $_POST['position'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repassword = $_POST['repass'];
        $target_dir = "../public/uploads/";
        $extension = '.'.pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
        $file_name = 'admin'.date('YmdHis').rand(0,1000).$extension;
        $target_file = $target_dir . basename($file_name);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        $sql = "SELECT email FROM admins WHERE email='$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            set_flash_session('error','Email đã tồn tại.');
        }
        else{
            if(strlen($password) >= 8){
                if($password == $repassword){
                    $sql = "INSERT INTO admins(fname,lname,DOB,gender,position,address,phone,email,password,avatar,role)
                    VALUES ('$fname','$lname','$DOB','$gender','$position','$address','$phone','$email',md5('$password'),'$file_name','$role')";
        
                    if($conn->query($sql) === TRUE){
                        set_flash_session('mess_flash','Nhân sự mới đã được thêm.');
                        redirect('add-admin.php');
                    }else{
                        set_flash_session('error','Không thể thêm nhân sự.');
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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thành viên | Quản trị viên</title>
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
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="border-bottom pt-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="HR.php">Nhân sự</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm thành viên</li>
                        </ol>
                    </nav>
                    <div class="mt-4">
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
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-7 col-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12" id="ad-avatar">
                                            <img class="d-block rounded-pill" id="frame" src="../public/uploads/default-avatar.jpeg" alt="">
                                            <input type="file" accept="image/png, image/jpg, image/jpeg" name="fileToUpload" id="fileToUpload" class="form-control mt-2" onchange="preview(event)" required>
                                        </div>
                                        <div class="col-lg-8 col-md-12">
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="fname" class="form-label">Họ và tên lót:</label>
                                                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Nhập họ và tên lót" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="lname" class="form-label">Tên:</label>
                                                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Nhập tên" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="position" class="form-label">Chức vụ:</label>
                                                        <input type="text" class="form-control" id="position" name="position" placeholder="Nhập chức vụ" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="lname" class="form-label">Đặc quyền:</label>
                                                        <select class="form-select" name="role">
                                                            <option value="1">Đọc và ghi</option>
                                                            <option value="0">Chỉ đọc</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="DOB" class="form-label">Ngày sinh:</label>
                                                <input type="date" class="form-control" id="DOB" name="DOB" placeholder="Nhập ngày sinh" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Địa chỉ:</label>
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="gender" class="form-label">Giới tính:</label>
                                                <input type="text" class="form-control" id="gender" name="gender" placeholder="Nhập giới tính" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="phone" class="form-label">Số điện thoại:</label>
                                                <input type="number" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Nhập số email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu:</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="repass" class="form-label">Nhập lại mật khẩu:</label>
                                        <input type="password" class="form-control" id="repass" name="repass" placeholder="Nhập lại mật khẩu" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Lưu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="../public/js/main.js"></script>
</body>
</html>