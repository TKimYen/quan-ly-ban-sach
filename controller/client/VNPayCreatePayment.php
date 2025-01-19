<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../lib/Database.php';
require_once __DIR__ . '/../../model/GeneralModel.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$rootDir = basename(dirname(__DIR__, 2));   // Lấy tên thư mục chứa root chứa đồ án
$vnp_Returnurl = "http://localhost/" . $rootDir . "/index.php?page=checkout-submit";
$vnp_TmnCode = VNPAY_TMN_CODE; //Mã website tại VNPAY 
$vnp_HashSecret = VNPAY_HASH_SECRET; //Chuỗi bí mật
$vnp_OrderType = "other"; //Loại hình thanh toán 
$vnp_Locale = "vn";
$vnp_BankCode = "";  // Ngân hàng dành cho test
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];  // Tự động lấy địa chỉ host (localhost)

session_start();

if (isset($_SESSION['user']['idTK']) && !empty($_SESSION['user']['idTK'])) {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        if (isset($_POST['payment-method']) && !empty($_POST['payment-method'])) {
            $tamtinh = array_reduce(
                $_SESSION['cart']['products'],
                function ($carry, $item) {
                    return $carry + $item['soluong'] * $item['giaban'];
                },
                0
            );
            $phivanchuyen = (float)$_SESSION['cart']['phi_van_chuyen'];
            $tongtien = $tamtinh + $phivanchuyen;
            $phuong_thuc_tt = $_POST['payment-method'];

            if ($phuong_thuc_tt === 'cod') {
                // Lưu đơn hàng vào database
                $vnp_Returnurl .= "&payment_method=cod";
                header('Location: ' . $vnp_Returnurl);
                die();
            } else if ($phuong_thuc_tt === 'ck') {
                $vnp_Returnurl .= "&payment_method=ck";
                $vnp_TxnRef = uniqid('GD'); //Mã đơn hàng
                $vnp_OrderInfo = urlencode("KH" . $idTK . " thanh toan " . "DH" . $idDH);  // Nội dung thanh toán
                $vnp_Amount = $tongtien * 100;

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef,
                );
                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }

                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00',
                    'message' => 'success',
                    'data' => $vnp_Url
                );
                header('Location: ' . $vnp_Url);
                die();
            } else {
                header('Location: index.php?page=cart');
                die();
            }
        } else {
            header('Location: index.php?page=cart');
            die();
        }
    } else {
        header('Location: index.php?page=cart');
        die();
    }
} else {
    header('Location: index.php?page=signIn');
    die();
}