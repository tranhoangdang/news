<?php
    include('database/conn.php');

    $news = $_GET['id'];
    $sql = "SELECT topics.id AS topicID, categories.id AS categoryID
    FROM topics JOIN news ON topics.id=news.topicID JOIN categories ON categories.id=topics.categoryID
    WHERE news.id = '$news'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $topic = $row['topicID'];
    $category = $row['categoryID'];

    $update = "UPDATE news SET view=view+1 WHERE id=$news";
    mysqli_query($conn,$update);
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
    <div class="container-fluid" id="article-bg">
        <div class="row">
            <div class="col-md-6 col-12 ms-auto">
                <nav class="mt-4" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php
                            $sql = "SELECT id, name
                            FROM categories WHERE id='$category'";
                            $result = $conn->query($sql);
                            if($row = $result->fetch_assoc()){
                        ?>
                        <li class="breadcrumb-item"><a href="category.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                        <?php
                            }
                        ?>
                        <?php
                            $sql = "SELECT id, name
                            FROM topics WHERE id='$topic'";
                            $result = $conn->query($sql);
                            if($row = $result->fetch_assoc()){
                        ?>
                        <li class="breadcrumb-item"><a href="topic.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                        <?php
                            }
                        ?>
                    </ol>
                </nav>
                <?php
                    $sql = "SELECT news.title AS title, news.summary AS summary, news.image AS image,
                    news.content AS content, news.created_at AS created_at, admins.fname AS fname, admins.lname AS lname
                    FROM news JOIN admins ON news.adminID=admins.id WHERE news.id='$news'";
                    $result = $conn->query($sql);
                    if($row = $result->fetch_assoc()){
                ?>
                <div class="d-flex justify-content-between">
                    <span><i class="bi bi-clock-fill"></i> <?= $row['created_at'] ?></span>
                    <?php
                        if(!isset($_COOKIE['news'])){
                    ?>
                        <a href="reaction.php?article=<?= $news ?>" type="button" class="btn">
                            <i class="bi bi-heart"></i>
                        </a>
                    <?php
                        }
                        else{
                            $sql = "SELECT articleID,userID FROM reactions WHERE articleID='$news' AND userID='$user'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0){
                            ?>
                            <a href="reaction.php?article=<?= $news ?>" type="button" class="btn">
                                <i class="bi bi-heart-fill"></i>
                            </a>
                            <?php
                            }
                            else{
                            ?>
                            <a href="reaction.php?article=<?= $news ?>" type="button" class="btn">
                                <i class="bi bi-heart"></i>
                            </a>
                            <?php
                            }
                        }
                    ?>
                </div>
                <div class="mt-3">
                    <h1 class="mb-3"><?= $row['title'] ?></h1>
                    <p class="mb-3"><?= $row['summary'] ?></p>
                    <img class="img-fluid mb-3" src="public/uploads/<?= $row['image'] ?>" alt="">
                    <p class="mb-3"><?= $row['content'] ?></p>
                </div>
                <div class="d-flex justify-content-end mt-5 mb-5">
                    <p class="fw-bold fst-italic"><?= $row['fname'] . " " . $row['lname']?></p>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="col-md-3 col-12 me-auto mt-5">
                <h4 class="mb-3 ms-3">Cùng chủ đề</h4>
                <?php
                    $sql = "SELECT id,image,title,view FROM news WHERE is_active=1 AND topicID='$topic'
                    EXCEPT(SELECT id,image,title,view FROM news WHERE id='$news')
                    ORDER BY view DESC LIMIT 3";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                    ?>
                    <div class="card article">
                        <a href="article.php?id=<?= $row['id'] ?>">
                            <img class="card-img-top" src="public/uploads/<?= $row['image'] ?>" alt="">
                            <div class="card-body">
                                <h5 class="article-title"><?= $row['title'] ?></h5>
                            </div>
                        </a>
                    </div>
                    <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="../public/js/main.js"></script>
</body>
</html>