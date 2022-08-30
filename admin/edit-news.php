<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }

    $news = $_GET['id'];

    if(isset($_POST['submit'])){
        $title = $_POST['title'];
        $topic = $_POST['topic'];
        $content = $_POST['content'];
        $summary = $_POST['summary'];

        $sql = "SELECT title FROM news WHERE title='$title'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            set_flash_session('error','Tiêu đề này đã tồn tại.');
        }
        else{
            $sql = "UPDATE news SET title = '$title', topicID = '$topic', content = '$content', summary = '$summary' WHERE id = '$news'";

            if($conn->query($sql) === TRUE){
                set_flash_session('mess_flash','Tin tức đã được chỉnh sửa.');
                redirect('edit-news.php?id=' . $news);
            } else {
                set_flash_session('error','Không thể chỉnh sửa tin tức này.');
            }
        }

    }

    if(isset($_POST['change'])){
        $target_dir = "../public/uploads/";
        $extension = '.'.pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
        $file_name = 'cover'.date('YmdHis').rand(0,1000).$extension;
        $target_file = $target_dir . basename($file_name);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        $sql = "UPDATE news SET image = '$file_name' WHERE id = '$news'";

        if($conn->query($sql) === TRUE){
            redirect('edit-news.php?id=' . $news);
        } else {
            set_flash_session('error','Không thể chỉnh sửa bài báo này.');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa tin tức | Quản trị viên</title>
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
                            <li class="breadcrumb-item"><a href="news.php">Tin tức</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa đổi tin tức</li>
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
                                <div class="col-md-5 col-12">
                                    <div class="information">
                                        <?php
                                            $sql = "SELECT view, created_at, updated_at FROM news WHERE id = '$news'";
                                            $result = $conn->query($sql);
                                            if($result->num_rows > 0){
                                                while($row = $result->fetch_assoc()){
                                        ?>
                                            <p class="datetime">Ngày thêm: <?= $row['created_at'] ?></p>
                                            <p class="datetime">Chỉnh sửa gần đây: <?= isset($row['updated_at']) ? $row['updated_at'] : 'Chưa chỉnh sửa' ?></p>
                                            <p class="datetime">Lượt xem: <?= $row['view']?></p>
                                        <?php            
                                                }
                                            }
                                        ?>
                                        <?php
                                            $sql = "SELECT admins.id AS id, admins.fname AS fname, admins.lname AS lname
                                            FROM admins JOIN news ON admins.id=news.adminID
                                            WHERE news.id = $news";
                                            $result = $conn->query($sql);
                                            if($result->num_rows > 0){
                                                while($row = $result->fetch_assoc()){
                                        ?>
                                            <p class="datetime">Tác giả: <?= $row['fname'] . ' ' . $row['lname'] ?></p>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php
                                        $sql = "SELECT title,content,summary,image FROM news WHERE id = '$news'";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control" id="title" name="title" aria-describedby="title" placeholder="Nhập tiêu đề" value="<?= $row['title'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Thuộc chủ đề</label>
                                            <select class="form-select" name="topic">
                                                <?php
                                                    $sql = "SELECT categories.id AS id, categories.name AS name
                                                    FROM topics JOIN news ON topics.id=news.topicID JOIN categories ON categories.id=topics.categoryID
                                                    WHERE news.id = '$news'";
                                                    $result = $conn->query($sql);
                                                    if($result->num_rows > 0){
                                                        while($rows = $result->fetch_assoc()){
                                                            $categoryID = $rows['id'];
                                                ?>
                                                <optgroup label="<?= $rows['name'] ?>">
                                                    <?php
                                                        $sql = "SELECT topics.id AS id, topics.name AS name
                                                        FROM topics JOIN news ON topics.id=news.topicID JOIN categories ON categories.id=topics.categoryID
                                                        WHERE news.id = '$news' AND categories.id='$categoryID'";
                                                        $result = $conn->query($sql);
                                                        if($result->num_rows > 0){
                                                            while($rows = $result->fetch_assoc()){
                                                    ?>
                                                    <option value="<?= $rows['id'] ?>"><?= $rows['name'] ?></option>
                                                    <?php
                                                            }
                                                        }

                                                    ?>
                                                    <?php
                                                        $sql = "SELECT topics.id AS id,topics.name AS name FROM topics JOIN categories ON topics.categoryID=categories.id
                                                        WHERE topics.is_active=1 AND categories.is_active=1 AND categories.id='$categoryID'
                                                        EXCEPT (SELECT topics.id AS id, topics.name AS name
                                                        FROM topics JOIN news ON topics.id=news.topicID JOIN categories ON categories.id=topics.categoryID
                                                        WHERE news.id = '$news' AND topics.is_active=1 AND categories.id='$categoryID')";
                                                        $result = $conn->query($sql);
                                                        if($result->num_rows > 0){
                                                            while($rows = $result->fetch_assoc()){
                                                    ?>
                                                    <option value="<?= $rows['id'] ?>"><?= $rows['name'] ?></option>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </optgroup>
                                                <?php            
                                                        }
                                                    }
                                                ?>
                                                <?php
                                                    $sql = "SELECT id, name FROM categories WHERE is_active = 1
                                                    EXCEPT (SELECT categories.id AS id, categories.name AS name
                                                    FROM topics JOIN news ON topics.id=news.topicID JOIN categories ON categories.id=topics.categoryID
                                                    WHERE news.id = '$news')";
                                                    $result = $conn->query($sql);
                                                    if($result->num_rows > 0){
                                                        while($rows = $result->fetch_assoc()){
                                                            $categoryID = $rows['id'];
                                                ?>
                                                <optgroup label="<?= $rows['name'] ?>">
                                                <?php
                                                        $sql = "SELECT topics.id AS id, topics.name AS name
                                                        FROM topics JOIN categories ON categories.id=topics.categoryID
                                                        WHERE topics.is_active = 1 AND categories.is_active = 1 AND categories.id='$categoryID'";
                                                        $result = $conn->query($sql);
                                                        if($result->num_rows > 0){
                                                            while($rows = $result->fetch_assoc()){
                                                    ?>
                                                    <option value="<?= $rows['id'] ?>"><?= $rows['name'] ?></option>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </optgroup>
                                                <?php            
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="summary" class="form-label">Tóm tắt:</label>
                                            <textarea name="summary" class="form-control" id="summary" cols="30" rows="3"><?= $row['summary'] ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Ảnh bìa:</label>
                                            <button type="button" class="d-block btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#changeCover">
                                                Đổi ảnh bìa
                                            </button>
                                            <img src="../public/uploads/<?= $row['image'] ?>" alt="" style="width:100%" class="mt-4">
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-12">
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Nội dung:</label>
                                            <textarea name="content" class="form-control" id="content"><?= $row['content'] ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block" name="submit">Lưu</button>
                                    </div>
                                <?php            
                                        }
                                    }
                                ?>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>

    </main>

    <div class="modal" id="changeCover">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Đổi ảnh bìa</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="file" class="form-control" accept="image/png, image/jpg, image/jpeg" name="fileToUpload" id="fileToUpload" onchange="preview(event)" required>
                    <img class="mt-4" id="frame">
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
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script src="../public/js/main.js"></script>
    <script>
    ClassicEditor
        .create( document.querySelector( '#content' ) )
        .catch( error => {
            console.error( error );
        } );
    </script>
</body>
</html>