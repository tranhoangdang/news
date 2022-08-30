<?php
    $id = $_SESSION['admin'];
    $sql = "SELECT avatar FROM admins WHERE id = '$id'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $img = $row['avatar'];
?>

<header class="navbar navbar-expand-md">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand" id="brand">
            <img src="../public/images/logo.png" alt="Logo">
            <h1>Tin tức</h1>
        </a>
        <div class="d-flex jusity-content-end">
            <div class="dropdown me-3">
                <div class="dropdown" data-bs-toggle="dropdown">
                    <img src="../public/uploads/<?= $img ?>" id="avatar-dropdown" alt="">
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php?id=<?= $id ?>">Tài khoản</a></li>
                    <li><a class="dropdown-item" href="change-password.php">Đổi mật khẩu</a></li>
                    <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                </ul>
            </div>
            <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>