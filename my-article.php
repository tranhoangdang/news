<?php
    include('database/conn.php');
    require_once('functions.php');
    require_once('pagination.php');

    if(!isset($_COOKIE['news'])){
        redirect('login.php');
    }

    $user = $_GET['id'];

    $query = "SELECT news.id AS id FROM news JOIN reactions ON news.id=reactions.articleID JOIN users ON users.id=reactions.userID
    WHERE users.id='$user'";
    $results_per_page = 5;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài viết đã lưu</title>
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
            <div class="col-md-8 m-auto">
                <h2 class="d-flex justify-content-center heading">Bài viết đã thích</h2>
                <?php
                    $sql = "SELECT news.id AS id,news.title AS title, news.image AS image,news.summary AS summary
                    FROM news JOIN reactions ON news.id=reactions.articleID JOIN users ON users.id=reactions.userID
                    WHERE users.id='$user' AND news.is_active=1 ORDER BY reactions.id DESC
                    LIMIT " . first_page($results_per_page) . ',' . $results_per_page;
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                ?>
                <table class="table table-borderless">
                    <tr>
                        <td>
                            <a class="list-article" href="article.php?id=<?= $row['id'] ?>">
                                <img src="public/uploads/<?= $row['image'] ?>" alt="">
                            </a>
                        </td>
                        <td>
                            <a class="list-article" href="article.php?id=<?= $row['id'] ?>">
                                <h5><?= $row['title'] ?></h5>
                                <p class="d-none d-lg-block"><?= $row['summary'] ?></p>
                            </a>
                        </td>
                    </tr>
                </table>
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
                    <li class="page-item"><a class="page-link" href="my-article.php?id=<?= $user ?>&page=<?= $page ?>"><?= $page ?></a></li>
                    <?php
                        }
                    ?>
                </ul>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>

    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>