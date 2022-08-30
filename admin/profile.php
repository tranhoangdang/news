<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }

    $admin = $_GET['id'];
    $id = $_SESSION['admin'];
    $sql = "SELECT role FROM admins WHERE id='$id'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $permission = $row['role'];

    if(isset($_POST['submit'])){
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $position = $_POST['position']; 
        $DOB = $_POST['DOB']; 
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $sql = "UPDATE admins SET fname='$fname', lname='$lname', position='$position',
        DOB='$DOB', gender='$gender', address='$address', phone='$phone' WHERE id = '$admin'";

        if($conn->query($sql) === TRUE){
            set_flash_session('mess_flash','Thông tin đã được chỉnh sửa.');
            redirect('profile.php?id=' . $admin);
        } else {
            set_flash_session('error','Không thể cập nhật thông tin cá nhân.');
        }
    }

    if(isset($_POST['change'])){
        $target_dir = "../public/uploads/";
        $extension = '.'.pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
        $file_name = 'admin'.date('YmdHis').rand(0,1000).$extension;
        $target_file = $target_dir . basename($file_name);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        $sql = "UPDATE admins SET avatar = '$file_name' WHERE id = '$admin'";

        if($conn->query($sql) === TRUE){
            redirect('profile.php?id=' . $admin);
        } else {
            set_flash_session('error','Không thể thay đổi ảnh đại diện.');
        }
    }

    if(isset($_GET['status']) && $id != $admin && $permission==1){
        $sql = "SELECT is_active FROM admins WHERE id='$admin'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);
        $status = $row['is_active'];
        if($status == 1){
            $sql="UPDATE admins SET is_active=-1 WHERE id='$admin'";
            mysqli_query($conn,$sql);
            redirect('profile.php?id='.$admin);
        }
        else{
            $sql="UPDATE admins SET is_active=1 WHERE id='$admin'";
            mysqli_query($conn,$sql);
            redirect('profile.php?id='.$admin);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân | Quản trị viên</title>
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
                    <h2 class="heading">Thông tin cá nhân</h2>
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
                        <div class="row">
                            <?php
                                $sql = "SELECT fname, lname, position, DOB, gender, address, phone, email, avatar, role, is_active
                                FROM admins WHERE id = '$admin'";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                            ?>
                            <div class="col-md-7 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12" id="ad-avatar">
                                        <img src="<?= '../public/uploads/'.$row['avatar'] ?>" alt="">  
                                        <?php
                                        if($permission==1){
                                        ?>
                                            <input type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#updateAvatar" value="Cập nhật ảnh đại diện">
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-lg-8 col-md-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="fname" class="form-label">Họ và tên lót:</label>
                                                    <input type="text" class="form-control" id="fname" name="fname" value="<?= $row['fname'] ?>" placeholder="Nhập họ và tên lót" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="lname" class="form-label">Tên:</label>
                                                    <input type="text" class="form-control" id="lname" name="lname" value="<?= $row['lname'] ?>" placeholder="Nhập tên" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="position" class="form-label">Chức vụ:</label>
                                            <input type="text" class="form-control" id="position" name="position" value="<?= $row['position'] ?>" placeholder="Nhập chức vụ" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số điện thoại:</label>
                                            <input type="number" class="form-control" id="phone" name="phone" value="<?= $row['phone'] ?>" placeholder="Nhập số điện thoại" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email:</label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?= $row['email'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <div class="mb-3">
                                    <label for="DOB" class="form-label">Ngày sinh:</label>
                                    <input type="date" class="form-control" id="DOB" name="DOB" value="<?= $row['DOB'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Giới tính:</label>
                                    <input type="text" class="form-control" id="gender" name="gender" value="<?= $row['gender'] ?>" placeholder="Nhập giới tính" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ:</label>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= $row['address'] ?>" placeholder="Nhập địa chỉ" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Trạng thái:</label>
                                    <?php
                                        if($row['is_active']==1){
                                    ?>
                                    <td>
                                        <a href="profile.php?id=<?= $admin ?>&status=<?= $admin ?>" type="button" class="btn btn-sm btn-primary form-control">ON</a>
                                    </td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                    <td>
                                        <a href="profile.php?id=<?= $admin ?>&status=<?= $admin ?>" type="button" class="btn btn-sm btn-danger form-control">OFF</a>
                                    </td>
                                    <?php
                                        }
                                    ?>
                                    <td>
                                    <?php
                                        if($permission==1){
                                    ?>
                                        <button type="submit" class="btn btn-primary btn-block" name="submit">Lưu</button>
                                    <?php
                                        }
                                    ?>
                                    </td>
                                </div>
                            </div>
                            <?php            
                                    }
                                }
                            ?>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </main>

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
                        <img style="max-width: 300px; height:300px" class="rounded-pill mt-4" id="frame">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="../public/js/main.js"></script>
</body>
</html>