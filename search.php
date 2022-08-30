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
    <div class="container">
        <div class="col-lg-8 m-auto">
            <h1 class="mt-5">Tìm kiếm</h1>
            <form class="d-flex" method="GET">
                <input class="form-control me-2" name="search" type="text" placeholder="Tìm kiếm">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <?php
            if(isset($_REQUEST['search'])){
            ?>
            <table class="table table-borderless mt-3">
                <tbody>
                    <?php
                    $search = $_GET['search'];
                    $sql = "SELECT id,image,title,summary FROM news WHERE is_active=1 AND title LIKE '%$search%'";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                    ?>
                    <tr>
                        <td>
                            <a class="list-article" href="article.php?id=<?= $row['id'] ?>">
                                <img src="public/uploads/<?= $row['image'] ?>" alt="">
                            </a>
                        </td>
                        <td>
                            <a class="list-article" href="article.php?id=<?= $row['id'] ?>">
                                <h5><?= $row['title'] ?></h5>
                                <p class="d-none d-md-block"><?= $row['summary'] ?></p>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    else{
                    ?>
                    <td colspan="2" class="d-flex justify-content-center">No data available</td>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            }
            ?>
        </div>
    </div>
    <?php include('components/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="public/js/main.js"></script>
</body>
</html>