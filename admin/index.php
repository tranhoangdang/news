<?php
    session_start();
    include('../database/conn.php');
    require_once('../functions.php');

    if(!isset($_SESSION['admin'])){
        redirect('login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ | Quản trị viên</title>
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
            <div class="col-lg-2 col-md-3">
                <?php include('components/sidebar.php'); ?>
            </div>
            <div class="col-lg-10 col-md-9">
                <article class="ad-article">
                    <h2 class="heading">Trang chủ</h2>
                    <div class="container">
                        <div class="row">
                            <?php
                                $sql = "SELECT COUNT(id) AS total FROM news";
                                $result = $conn->query($sql);
                                $row = mysqli_fetch_assoc($result);
                            ?>
                            <div class="col-md-3 col-sm-6 figures">
                                <a class="card bg-primary" href="news.php">
                                    <div class="card-header">
                                        <h5>Bài viết</h5>
                                    </div>
                                    <div class="card-body"><?= $row['total'] ?> bài viết</div>
                                </a>
                            </div>
                            <?php
                                $sql = "SELECT SUM(view) AS total FROM news";
                                $result = $conn->query($sql);
                                $row = mysqli_fetch_assoc($result);
                            ?>
                            <div class="col-md-3 col-sm-6 figures">
                                <a class="card bg-warning">
                                    <div class="card-header">
                                        <h5>Lượt xem</h5>
                                    </div>
                                    <div class="card-body"><?= $row['total'] ?> lượt xem</div>
                                </a>
                            </div>
                            <?php
                                $sql = "SELECT COUNT(id) AS total FROM users";
                                $result = $conn->query($sql);
                                $row = mysqli_fetch_assoc($result);
                            ?>
                            <div class="col-md-3 col-sm-6 figures">
                                <a class="card bg-success" href="reader.php">
                                    <div class="card-header">
                                        <h5>Độc giả</h5>
                                    </div>
                                    <div class="card-body"><?= $row['total'] ?> độc giả đã đăng ký</div>
                                </a>
                            </div>
                            <?php
                                $sql = "SELECT COUNT(id) AS total FROM reactions";
                                $result = $conn->query($sql);
                                $row = mysqli_fetch_assoc($result);
                            ?>
                            <div class="col-md-3 col-sm-6 figures">
                                <a class="card bg-danger">
                                    <div class="card-header">
                                        <h5>Tương tác</h5>
                                    </div>
                                    <div class="card-body"><?= $row['total'] ?> yêu thích</div>
                                </a>
                            </div>
                            <?php
                                $sql = "SELECT id, title, summary, image FROM news WHERE is_active=1 ORDER BY view DESC LIMIT 1";
                                $result = $conn->query($sql);
                                $row = mysqli_fetch_assoc($result);
                            ?>
                            <div class="col-md-6 d-flex align-items-stretch highlight">
                                <a class="card" href="../article.php?id=<?= $row['id'] ?>" target="_blank">
                                    <div class="card-header">
                                        <h5>Bài viết được xem nhiều nhất</h5>
                                    </div>
                                    <div class="card-body">
                                        <img class="card-img" src="../public/uploads/<?= $row['image'] ?>" alt="">
                                        <h5><?= $row['title'] ?></h5>
                                        <p><?= $row['summary'] ?></p>
                                    </div>
                                </a>
                            </div>
                            <?php
                                $sql = "SELECT reactions.articleID AS reaction, news.id AS id, news.title AS title, news.summary AS summary, news.image AS image
                                FROM news JOIN reactions ON news.id=reactions.articleID
                                WHERE is_active=1
                                GROUP BY reaction
                                ORDER BY reaction DESC
                                LIMIT 1";
                                $result = $conn->query($sql);
                                $row = mysqli_fetch_assoc($result);
                            ?>
                            <div class="col-md-6 d-flex align-items-stretch highlight">
                                <a class="card" href="../article.php?id=<?= $row['id'] ?>" target="_blank">
                                    <div class="card-header">
                                        <h5>Bài viết được yêu thích nhất</h5>
                                    </div>
                                    <div class="card-body">
                                        <img class="card-img" src="../public/uploads/<?= $row['image'] ?>" alt="">
                                        <h5><?= $row['title'] ?></h5>
                                        <p><?= $row['summary'] ?></p>
                                    </div>
                                </a>
                            </div>
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