<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vinabook</title>
    <link rel="shortcut icon" href="asset/quantri/img/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/1acf2d22a5.js" 
        crossorigin="anonymous"></script>
        <link rel="stylesheet" href="asset/client/css/KHCSS.css">
        <link href="https://cdn.jsdelivr.net/gh/HuongLamCoder/font-awesome-pro-6.5.2/fontawesome-pro-6.5.2-web/css/all.min.css" 
        rel="stylesheet" 
        type="text/css"/>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- Toast Message -->
        <link rel="stylesheet" href="asset/quantri/css/toast-msg.css">
        <script src="asset/quantri/js/toast-msg.js"></script>
</head>
<body>
    <!-- Toast message -->
    <div id="toast"></div>
    <!-- ... -->

    <header class="header">
        <!-- Dòng màu xanh hotline -->
        <div class="hotline-line">
            <i class="fa-duotone fa-solid fa-phone"></i>
            <span> Hotline: 1900 704421</span>
        </div>
        <!-- Navigation bar -->
        <div class="container navigation-bar">
            <div class="row">
                <!-- Vinabook logo -->
                <div class="col-3">
                    <div class="logo">
                        <a href="?page=home" class="navbar-brand">
                            <img src="asset/client/img/vinabook-logo.png" alt="">
                        </a>
                    </div>
                </div>
                <!-- Tìm kiếm sách cơ bản -->
                <div class="col-6">
                    <div class="srch-field">
                        <form class="srch-form">
                            <input type="hidden" name="page" value="search">
                            <input type="text" placeholder="Tìm kiếm sản phẩm, tác giả,..." name="kyw" required>
                            <button title="Tìm sách" class="btn srch-btn" name="srch">Tìm sách</button>
                        </form>
                    </div>
                </div>
                 <!-- 3 nút: Giỏ hàng - Đăng nhập - Đăng ký -->
                 <div class="col-3">
                    <ul class="nav-btn-list">
                        <li>
                            <?php if (isset($_SESSION['user']['tenTK']) && $_SESSION['user']['idNQ'] == 1):?>
                                <a href="?page=cart" class="btn btn-1"><i class="fa-light fa-cart-shopping"></i></a>
                            <?php else: ?>
                                <a href="index.php?page=login" class="btn btn-1"><i class="fa-light fa-cart-shopping"></i></a>
                            <?php endif ?>
                        </li>
                        <li>
                            <?php 
                            if (isset($_SESSION['user']['tenTK']) && $_SESSION['user']['idNQ'] == 1):?>
                                <a href="index.php?page=customerInfo"class="btn btn-1 signin-btn"><span><?php 
                                    $temp = explode(" ", $_SESSION['user']['tenTK']);
                                    echo $temp[sizeof($temp) - 1];
                                    ?></span></a>
                            <?php else: ?>
                                <a href="index.php?page=login" class="btn btn-1 signin-btn">Đăng nhập</a>
                            <?php endif ?>
                        </li>
                        <li>
                            <?php if (isset($_SESSION['user']['tenTK']) && $_SESSION['user']['idNQ'] == 1):?>
                                <a href="index.php?page=signOut" class="btn btn-2 signin-btn">Đăng xuất</a>
                            <?php else: ?>
                                <a href="index.php?page=signUp" class="btn btn-2 signup-btn">Đăng ký</a>
                            <?php endif ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>