<?php
    include('database/conn.php');
    require_once('functions.php');

    if(!isset($_COOKIE['news'])){
        redirect('login.php');
    }

    $user = $_GET['id'];

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $biography = isset($_POST['biography']) ? $_POST['biography'] : NULL;

        $sql = "UPDATE users SET name='$name', biography='$biography' WHERE id='$user'";
        if($conn->query($sql) === TRUE){
            set_flash_session('mess_flash','Thông tin cá nhân đã được cập nhật.');
            redirect('profile.php?id=' . $user);
        } else {
            set_flash_session('mess_flash','Không thể cập nhật thông tin cá nhân.');
        }
    }

    if(isset($_POST['change'])){
        $target_dir = "public/uploads/";
        $extension = '.'.pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
        $file_name = 'user'.date('YmdHis').rand(0,1000).$extension;
        $target_file = $target_dir . basename($file_name);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        $sql = "UPDATE users SET avatar = '$file_name' WHERE id = '$user'";

        if($conn->query($sql) === TRUE){
            set_flash_session('mess_flash','Ảnh đại diện đã được thay đổi.');
            redirect('profile.php?id=' . $user);
        } else {
            set_flash_session('mess_flash','Không thể cập nhật ảnh đại diện.');
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
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
    <?php include('components/navbar.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto mb-5">
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
                    if(isset($error)){
                ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Thất bại!</strong> <?= $error ?>
                </div>
                <?php        
                    }
                ?>
                <?php
                    $sql = "SELECT name,email,biography,avatar FROM users WHERE id='$user'";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                ?>
                <h2 class="heading">Thông tin tài khoản</h2>
                <img data-bs-toggle="modal" data-bs-target="#updateAvatar" id="avatar" src="<?= 'public/uploads/'.$row['avatar'] ?>" alt="">  
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên" value="<?= $row['name'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="biography" id="biography" rows="5" placeholder="Giới thiệu về bạn..."><?= $row['biography'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email của bạn</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?= $row['email'] ?>" readonly>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-block" name="submit">Lưu thay đổi</button>
                    </div>
                </form>
                <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="modal" id="updateAvatar">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Đổi ảnh đại diện</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="file" class="form-control" accept="image/png, image/jpg, image/jpeg" name="fileToUpload" id="fileToUpload" onchange="preview(event)" required>
                    <div class="d-flex justify-content-center">
                        <img class="rounded-pill mt-4 avatar" id="frame" style="max-width: 100%">
                    </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="change">Lưu</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Hủy</button>
                </form>
            </div>

            </div>
        </div>
    </div>

    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>