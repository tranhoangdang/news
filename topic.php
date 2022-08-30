<?php
    include('database/conn.php');
    require_once('pagination.php');

    $topic = $_GET['id'];
    $sql = "SELECT categoryID FROM topics WHERE id = '$topic'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $category = $row['categoryID'];

    $query = "SELECT news.id as id FROM categories JOIN topics ON categories.id=topics.categoryID JOIN news ON news.topicID=topics.id WHERE topics.id=$topic";
    $results_per_page = 6;
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
    <div class="container">
        <div class="topic">
            <?php
                $sql = "SELECT name FROM topics WHERE id='$topic'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
            ?>
            <h1><?= $row['name'] ?></h1>
            <?php
                    }
                }
            ?>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="row">
                <?php
                    $sql = "SELECT news.id as id, news.title as title, news.image as image, news.summary as summary, news.created_at AS created_at
                    FROM categories JOIN topics ON categories.id=topics.categoryID JOIN news ON news.topicID=topics.id
                    WHERE news.is_active = 1 AND topics.id=$topic ORDER BY created_at DESC
                    LIMIT " . first_page($results_per_page) . ',' . $results_per_page;
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                ?>
                    <div class="col-md-4 col-6 d-flex align-items-stretch article">
                        <a href="article.php?id=<?= $row['id'] ?>" class="card">
                            <img class="card-img-top height" src="public/uploads/<?= $row['image'] ?>" alt="">
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
                if(num_pages($query,$results_per_page) > 1){
                ?>
                <ul class="pagination d-flex justify-content-center">
                <?php
                        for($page = 1; $page <= num_pages($query,$results_per_page); $page++){
                    ?>
                    <li class="page-item"><a class="page-link" href="topic.php?id=<?= $topic ?>&page=<?= $page ?>"><?= $page ?></a></li>
                    <?php
                        }
                    ?>
                </ul>
                <?php
                    }
                ?>
                </div>
            </div>
            <div class="col-md-3 col-12 p-4">
                <h4>Chủ đề liên quan</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <?php
                        $sql = "SELECT id,name FROM topics WHERE is_active=1 AND categoryID='$category'
                        EXCEPT SELECT id,name FROM topics WHERE id='$topic'";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                    ?>
                        <a class="nav-link" href="topic.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a>
                    <?php
                            }
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>