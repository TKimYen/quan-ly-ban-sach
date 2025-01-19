<?php
session_start();
if(isset($_GET['page']) && ($_GET['page'] !== "")){
    if (in_array($_GET['page'], ['login', 'forgot_password', 'authentication_code', 'reset_password', 'logout'])) {
        include '../inc/quantri/Header.php';
        switch(trim($_GET['page'])){
            case 'login':
                if(isset($_SESSION)){
                    unset($_SESSION['admin']);
                    unset($_SESSION['permission']);
                    unset($_SESSION['forgotPassword']);
                    unset($_SESSION['function']);
                }
                include '../controller/quantri/AuthenController.php';
                break;
            case 'forgot_password':
            case 'authentication_code':
            case 'reset_password':
                include '../controller/quantri/ForgotPasswordController.php';
                break;
            
            default:
                header('Location: index.php?page=login');
                break;
        }
    } else if(!isset($_SESSION['admin']))
    header('Location: http://localhost/he-thong-quan-ly-ban-sach/quantri/index.php');
    else{
        include '../inc/quantri/Navigation.php';
        switch(trim($_GET['page'])){
            case 'role':
                include '../controller/quantri/RoleController.php';
                break;
            case 'account':
                include '../controller/quantri/AccountController.php';
                break;
            case 'author':
                include '../controller/quantri/AuthorController.php';
                break;
            case 'category':
                include '../controller/quantri/CategoryController.php';
                break;
            case 'supplier':
                include '../controller/quantri/SupplierController.php';
                break;
            case 'discount':
                include '../controller/quantri/DiscountController.php';
                break;
            case 'product':
                include '../controller/quantri/ProductController.php';
                break;
            case 'order':
                include '../controller/quantri/OrderController.php';
                break;
            case 'goodsreceivenote':
                include '../controller/quantri/GRNController.php';
                break;
             /* HUONG NGUYEN 28/11/2024 */
             case 'chart':
                include '../controller/quantri/ChartController.php';
                break;
            /* HUONG NGUYEN 28/11/2024 */
            case 'searchRole':
                include '../controller/quantri/RoleController.php';
                break;
            case 'searchAccount':
                include '../controller/quantri/AccountController.php';
                break;
            case 'searchCategory':
                include '../controller/quantri/CategoryController.php';
                break;
            case 'searchSupplier':
                include '../controller/quantri/SupplierController.php';
                break;
            case 'searchDiscount':
                include '../controller/quantri/DiscountController.php';
                break;
            case 'searchAuthor':
                include '../controller/quantri/AuthorController.php';
                break;
            case 'searchProduct':
                include '../controller/quantri/ProductController.php';
                break;
            case 'searchOrder':
                include '../controller/quantri/OrderController.php';
                break;
            case 'searchGRN':
                include '../controller/quantri/GRNController.php';
                break;
            default:
                header('Location: index.php?page=login');
                break;
        }
    }
}
else if(!isset($_GET['page']) && isset($_SESSION['admin'])){
    $chucnang = $_SESSION['permission'][0];
    $page = explode("_", $chucnang['tenCN'])[0];
    switch($page){
        case 'NQ': $page = 'role'; break;
        case 'TK': $page = 'account'; break;
        case 'TG': $page = 'author'; break;
        case 'TL': $page = 'category'; break;
        case 'NCC': $page = 'supplier'; break;
        case 'MGG': $page = 'discount'; break;
        case 'SP': $page = 'product'; break;
        case 'DH': $page = 'order'; break;
        case 'ST': $page = 'chart'; break;
    }
header('Location: index.php?page='.$page);
}
else{ 
    header('Location: index.php?page=login');
}
?> 