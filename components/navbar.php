<nav class="navbar navbar-expand-xl navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="d-block d-lg-none ms-3">
            <?php
            if(isset($_COOKIE['news'])){
                $user = $_COOKIE['news'];
            ?>
                <div class="dropdown">
                    <div class="dropdown" data-bs-toggle="dropdown">
                        <img src="public/uploads/<?= $img ?>" id="avatar-dropdown" alt="">
                    </div>
                    <ul class="dropdown-menu">
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
                <a href="login.php" class="icon-header text-white"><i class="bi bi-person-fill"></i> Đăng nhập</a>
            <?php
            }
            ?>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item ms-3 me-3">
                    <a class="nav-link" href="index.php"><i class="bi bi-house-door-fill"></i></a>
                </li>
                <?php
                    $sql = "SELECT id,name FROM categories WHERE is_active = 1";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                ?>
                <li class="nav-item me-3">
                    <a class="nav-link" href="category.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a>
                </li>
                <?php            
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>