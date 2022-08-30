<?php
    include('database/conn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức</title>
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
    <div class="container mb-5">
        <div class="row">
            <?php
                $sql = "SELECT id,image,title,summary,created_at FROM news
                WHERE is_active=1 ORDER BY created_at DESC LIMIT 2";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
            ?>
                <div class="col-lg-6 col-md-12 d-flex align-items-stretch article">
                    <a href="article.php?id=<?= $row['id'] ?>" class="card">
                        <img class="card-img-top" src="public/uploads/<?= $row['image'] ?>" alt="">
                        <div class="card-body">
                            <h5 class="article-title"><?= $row['title'] ?></h5>
                            <p class="article-summary"><?= $row['summary'] ?></p>
                        </div>
                    </a>
                </div>
            <?php            
                    }
                }
            ?>
            <?php
                $sql = "SELECT id,image,title,summary,created_at FROM news WHERE is_active=1
                EXCEPT(SELECT id,image,title,summary,created_at FROM news
                WHERE is_active=1 ORDER BY created_at DESC LIMIT 2)
                ORDER BY created_at DESC LIMIT 12";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
            ?>
                <div class="col-lg-3 col-md-4 col-6 d-flex align-items-stretch article">
                    <a class="card" href="article.php?id=<?= $row['id'] ?>">
                        <img class="card-img-top" src="public/uploads/<?= $row['image'] ?>" alt="">
                        <div class="card-body">
                            <h5 class="article-title"><?= $row['title'] ?></h5>
                            <p class="article-summary"><?= $row['summary'] ?></p>
                        </div>
                    </a>
                </div>
            <?php            
                    }
                }
            ?>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>