<?php
session_start();
include_once "inc/client/header.php";

if(isset($_GET['page'])&&($_GET['page']!=="")){
    if(in_array($_GET['page'], ['signUp', 'signUp_OTP', 'login', 'forgotPassword', 'show_OTPInputForm', 'show_changePasswordForm', 'search', 'home', 'productDetail']))
        switch(trim($_GET['page'])){   
            case 'signUp':
            case 'signUp_OTP':
            case 'login':
                require __DIR__ . '/controller/client/AuthenController.php';
                break;

            case 'forgotPassword':
            case 'show_OTPInputForm':
            case 'show_changePasswordForm':
                require __DIR__ . '/controller/client/ForgotPasswordController.php';
                break;
            
            case 'home':
            case 'search':
            case 'productDetail':
                require __DIR__ . "/controller/client/HomeController.php";
                break;
        }
    else if(isset($_SESSION['user']))
        switch(trim($_GET['page'])){   
            case 'orderHistory':
            case 'orderDetail':
            case 'customerInfo':
            case 'changePassword':
                require __DIR__ . '/controller/client/CustomerInfoController.php';
                break;
            
            case 'cart':
                require __DIR__ . "/controller/client/HomeController.php";
                break;
            case 'checkout-address':
            case 'checkout':
            case 'checkout-submit':
                require __DIR__ . '/controller/client/CheckoutController.php';
                break;
    
            case 'signOut':
                if(isset($_SESSION['user'])) unset($_SESSION['user']);
                if(isset($_SESSION['forgotPassword'])) unset($_SESSION['forgotPassword']);
                if(isset($_SESSION['cart'])) unset($_SESSION['cart']);
                if(isset($_SESSION['signUp'])) unset($_SESSION['signUp']);
                header("Location:index.php?page=home");
                break;
            
            default:
                header("Location:index.php?page=login");
                break;
        }
        
}
else header("Location:index.php?page=home");

include_once "inc/client/footer.php";

?>