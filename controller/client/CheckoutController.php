<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../model/GeneralModel.php';
include __DIR__ . '/../../model/City.php';
include __DIR__ . '/../../model/District.php';
include __DIR__ . '/../../model/Ward.php';
include __DIR__ . '/../../model/Order.php';
include __DIR__ . '/../../model/OrderDetail.php';
include __DIR__ . '/../../model/Product.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

class CheckoutController extends BaseController
{
    function __construct()
    {
        $this->folder = 'client';
    }

    function checkAction($action)
    {
        switch ($action) {
            case 'checkout-address':
                $this->checkoutAddress();
                break;
            case 'checkout':
                $this->checkout();
                break;
            case 'get-districts':
                $this->getDistricts();
                break;
            case 'get-wards':
                $this->getWards();
                break;
            case 'get-cart-total-weight':
                $this->getCartTotalWeight();
                break;
            case 'update-cart-info':
                $this->updateCartInfo();
                break;
            case 'checkout-submit':
                if (isset($_SESSION['user']) && $_SESSION['user']['idTK'] != '') {
                    if (isset($_SESSION['cart']['products']) && count($_SESSION['cart']['products']) > 0) {
                        $this->checkoutSubmit();
                    } else {
                        header("Location:index.php?page=home");
                    }
                } else {
                    header("Location:index.php?page=login");
                }
                break;
            default:
                $this->render('Home');
                break;
        }
    }

    function checkoutAddress()
    {
        if (isset($_SESSION['cart']['products']) && count($_SESSION['cart']['products']) > 0) {
            $this->render('CheckoutAddress');
        } else {
            header("Location:index.php?page=home");
        }
    }

    function checkout()
    {
        if (isset($_SESSION['cart']['products']) && count($_SESSION['cart']['products']) > 0) {
            $this->render('checkout');
        } else {
            header("Location:index.php?page=home");
        }
    }

    function getDistricts()
    {
        if (isset($_GET['province_id']) && $_GET['province_id'] != '') {
            $districts = District::getAllByCity((int)$_GET['province_id']);
            echo json_encode([
                'status' => 'success',
                'data' => $districts,
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ!',
            ]);
        }
        exit;
    }

    function getWards()
    {
        if (isset($_GET['district_id']) && $_GET['district_id'] != '') {
            $wards = Ward::getAllByDistrict((int)$_GET['district_id']);
            echo json_encode([
                'status' => 'success',
                'data' => $wards,
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ!',
            ]);
        }
        exit;
    }

    function getCartTotalWeight()
    {
        session_start();
        if (isset($_SESSION['cart']['products']) && count($_SESSION['cart']['products']) > 0) {
            $totalWeight = array_reduce($_SESSION['cart']['products'], function ($carry, $item) {
                return $carry + $item['soluong'] * $item['trongluong'];
            }, 0);
            echo json_encode([
                'status' => 'success',
                'data' => $totalWeight,
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ!',
            ]);
        }
        exit;
    }

    function updateCartInfo()
    {
        session_start();
        if (isset($_SESSION['cart']['products']) && count($_SESSION['cart']['products']) > 0) {
            $shippingFee = $_GET['shippingFee'] ?? 0;
            $diachi = $_GET['fullAddress'] ?? '';
            if ($shippingFee < 0 || $diachi == '') {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Thông tin cập nhật không hợp lệ!',
                ]);
                exit;
            } else {
                $_SESSION['cart']['phi_van_chuyen'] = $shippingFee;
                $_SESSION['cart']['diachi'] = $diachi;
                echo json_encode([
                    'status' => 'success',
                    'data' => $shippingFee,
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ!',
            ]);
        }
        exit;
    }

    function checkoutSubmit()
    {
        $idDH = GeneralModel::getNewAutoIncrementNumber('donhang');
        $tamtinh = array_reduce(
            $_SESSION['cart']['products'],
            function ($carry, $item) {
                return $carry + $item['soluong'] * $item['giaban'];
            },
            0
        );
        $idTT = 1;  //Chờ duyệt
        $phivanchuyen = (float)$_SESSION['cart']['phi_van_chuyen'];
        $ngaytao = date('Y-m-d');
        $ngaycapnhat = date('Y-m-d');
        $idTK = $_SESSION['user']['idTK'];
        $diachi = $_SESSION['cart']['diachi'];
        $phuong_thuc_tt = $_GET['payment_method'];

        $order = new Order(
            $idDH,
            $tamtinh,
            $idTT,
            $phivanchuyen,
            $ngaytao,
            $ngaycapnhat,
            $idTK,
            null,
            $diachi,
            $phuong_thuc_tt
        );
        if ($_GET['payment_method'] == 'cod') {
            $order->saveOrder();

            foreach ($_SESSION['cart']['products'] as $item) {
                $orderDetail = new OrderDetail(
                    $idDH,
                    (int)$item['idSach'],
                    (int)$item['soluong'],
                    (float)$item['giaban']
                );
                $orderDetail->saveOrderDetail();
                // Cập nhật số lượng sách trong kho
                Product::updateStock((int)$item['idSach'], (int)$item['soluong']);
            }
        } else if ($_GET['payment_method'] == 'ck') {
            $vnp_SecureHash = $_GET['vnp_SecureHash'];
            $inputData = array();
            foreach ($_GET as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
    
            $secureHash = hash_hmac('sha512', $hashData, VNPAY_HASH_SECRET);
    
            // Kiểm tra tình trạng thanh toán
            if ($secureHash == $vnp_SecureHash) {
                if ($_GET['vnp_ResponseCode'] == '00') {
                    $order->saveOrder();
    
                    foreach ($_SESSION['cart']['products'] as $item) {
                        $orderDetail = new OrderDetail(
                            $idDH,
                            (int)$item['idSach'],
                            (int)$item['soluong'],
                            (float)$item['giaban']
                        );
                        $orderDetail->saveOrderDetail();
                        // Cập nhật số lượng sách trong kho
                        Product::updateStock((int)$item['idSach'], (int)$item['soluong']);
                    }
    
                }
            }
        }
        unset($_SESSION['cart']);
        require_once __DIR__ . '/../../view/client/CheckoutSubmit.php';
    }
}

$checkoutController = new CheckoutController();
if (!isset($_GET['page'])) $action = 'home';
else $action = $_GET['page'];
$checkoutController->checkAction($action);