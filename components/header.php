<?php
    if(isset($_COOKIE['news'])){
        $id = $_COOKIE['news'];
        $sql = "SELECT id,avatar,is_active FROM users WHERE id = '$id'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);
        $img = $row['avatar'];
        $status = $row['is_active'];
        if($status != 1){
            setcookie("login", '', time()-1);
        }
        else{
            setcookie("login", $row['id'], time() + (86400*30));
        }
    }
?>
<div>
    <div class="navbar">
        <a href="index.php" class="navbar-brand me-auto" id="brand">
            <img src="public/images/logo.png" alt="Logo">
            <h1>Tin tức</h1>
        </a>
        <a href="search.php" class="icon-header ms-auto"><i class="bi bi-search"></i></a>
        <div class="d-none d-lg-block me-3">
            <?php
            if(isset($_COOKIE['news'])){
                $user = $_COOKIE['news'];
            ?>
                <div class="dropdown">
                    <div class="dropdown" data-bs-toggle="dropdown">
                        <img src="public/uploads/<?= $img ?>" id="avatar-dropdown" alt="">
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php?id=<?= $id ?>">Tài khoản</a></li>
                        <li><a class="dropdown-item" href="my-article.php?id=<?= $id ?>">Bài viết đã thích</a></li>
                        <li><a class="dropdown-item" href="change-password.php">Đổi mật khẩu</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            <?php
            }
            else{
            ?>
                <a href="login.php" class="icon-header"><i class="bi bi-person-fill"></i> Đăng nhập</a>
            <?php
            }
            ?>
        </div>
    </div>
</div>