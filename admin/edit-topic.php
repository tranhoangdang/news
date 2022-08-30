<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }

    $topic = $_GET['id'];

    if(isset($_POST['edit'])){
        $name = $_POST['name'];
        $categoryID = $_POST['category'];
        $admin = $_SESSION['admin'];
        $sql = "UPDATE topics SET name = '$name',
        categoryID = '$categoryID' WHERE id = $topic";
        

        $sql = "SELECT name FROM topics WHERE name='$name' AND categoryID=$categoryID";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            set_flash_session('error','Chủ đề này đã tồn tại.');
        }
        else{
            $sql = "UPDATE topics SET name = '$name' WHERE id = '$topic'";

            if($conn->query($sql) === TRUE){
                set_flash_session('mess_flash','Chủ đề "' . $name . '" đã được chỉnh sửa.');
                redirect('edit-topic.php?id=' . $topic);
            } else {
                set_flash_session('error','Không thể chỉnh sửa chủ đề này.');
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
    <title>Chỉnh sửa chủ đề | Quản trị viên</title>
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
                            <li class="breadcrumb-item"><a href="topic.php">Chủ đề</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa đổi chủ đề</li>
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
                        <div class="information">
                            <?php
                                $sql = "SELECT created_at,updated_at FROM topics WHERE id = $topic";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                            ?>
                            <p>Ngày thêm: <?= $row['created_at'] ?></p>
                            <p>Chỉnh sửa gần đây: <?= isset($row['updated_at']) ? $row['updated_at'] : 'Chưa chỉnh sửa' ?></p>
                            <?php
                                    }
                                }
                            ?>
                            <?php
                                $sql = "SELECT admins.id AS id, admins.fname AS fname, admins.lname AS lname
                                FROM admins JOIN Topics ON admins.id=topics.adminID
                                WHERE topics.id = $topic";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                            ?>
                            <p>Người thêm: <?= $row['fname'] . ' ' . $row['lname'] ?></p>
                            <?php
                                    }
                                }
                            ?>
                        </div>
                        <form method="POST">
                            <div class="col-lg-6 col-12 m-auto">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên chủ đề</label>
                                    <?php
                                        $sql = "SELECT name FROM topics WHERE id = $topic";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" aria-describedby="name" placeholder="Nhập tên chủ đề" required>
                                    <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Thuộc chuyên mục</label>
                                    <select class="form-select" name="category">
                                    <?php
                                        $sql = "SELECT topics.categoryID as categoryID, categories.name as category
                                        FROM topics JOIN categories ON topics.categoryID=categories.id
                                        WHERE topics.id = $topic";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                        <option value="<?= $row['categoryID'] ?>"><?= $row['category'] ?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                    <?php
                                        $sql = "SELECT id, name FROM categories WHERE is_active = 1
                                        EXCEPT SELECT topics.categoryID as categoryID, categories.name as category
                                        FROM topics JOIN categories ON topics.categoryID=categories.id
                                        WHERE topics.id = $topic";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                    </select>
                                </div>
                            
                                <button type="submit" class="btn btn-primary btn-block" name="edit">Lưu</button>
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