<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }

    if(isset($_POST['submit'])){
        $admin = $_SESSION['admin'];
        $title = $_POST['title'];
        $topic = $_POST['topic'];
        $content = $_POST['content'];
        $summary = $_POST['summary'];
        $status = $_POST['status'];
        $target_dir = "../public/uploads/";
        $extension = '.'.pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
        $file_name = 'cover'.date('YmdHis').rand(0,1000).$extension;
        $target_file = $target_dir . basename($file_name);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        $sql = "SELECT title FROM news WHERE title='$title'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            set_flash_session('error','Tiêu đề này đã tồn tại.');
        }
        else{
            $sql = "INSERT INTO news (title,image,content,summary,is_active,topicID,adminID)
            VALUES ('$title','$file_name','$content','$summary','$status','$topic','$admin')";

            if($conn->query($sql) === TRUE){
                set_flash_session('mess_flash','Tin tức đã được thêm.');
                redirect('add-news.php');
            } else {
                $error = $conn->error;
                set_flash_session('error','Không thể thêm tin tức này.');
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
    <title>Thêm tin tức | Quản trị viên</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Thêm tin tức</li>
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
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Tiêu đề</label>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="summary" class="form-label">Tóm tắt:</label>
                                        <textarea name="summary" class="form-control" id="summary" cols="30" rows="3"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Nội dung:</label>
                                        <textarea name="content" class="form-control" id="content"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Ảnh bìa:</label>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg" name="fileToUpload" id="fileToUpload" class="form-control" onchange="preview(event)" required>
                                        <img src="../public/images/default.jpeg" alt="" class="mt-4" id="frame">
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Chủ đề</label>
                                        <select class="form-select" name="topic">
                                            <?php
                                                $category = "SELECT id, name FROM categories
                                                WHERE is_active=1";
                                                $resultCategory = $conn->query($category);
                                                if($resultCategory->num_rows > 0){
                                                    while($rows = $resultCategory->fetch_assoc()){
                                                        $categoryID = $rows['id'];
                                            ?>
                                            <optgroup label="<?= $rows['name'] ?>">
                                                <?php
                                                    $topic = "SELECT topics.id AS id,topics.name AS name FROM topics JOIN categories ON topics.categoryID=categories.id
                                                    WHERE topics.is_active=1 AND categories.id='$categoryID'";
                                                    $resultTopic = $conn->query($topic);
                                                    if($resultTopic->num_rows > 0){
                                                        while($rows = $resultTopic->fetch_assoc()){
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
                                            <label for="status" class="form-label">Trạng thái</label>
                                            <select class="form-select" name="status">
                                                <option value="1">Hiển thị</option>
                                                <option value="-1">Tắt</option>
                                            </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Lưu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>


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