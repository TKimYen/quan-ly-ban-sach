<?php
include __DiR__.'/../BaseController.php';
include __DiR__.'/../../model/Order.php';
include __DiR__.'/../../model/OrderDetail.php';
include __DiR__.'/../../model/OrderStatus.php';
include __DiR__.'/../../model/Account.php';
include __DiR__.'/../../model/Product.php';

    class OrderController extends BaseController{

        function __construct()
        {
            $this->folder = 'quantri';
        }

        function index(){
            $orders = Order::getAll();
            $list = [];
            if($orders != NULL){
                foreach($orders as $order)
                    $list[] = OrderStatus::findByID($order->getIdTT());
            }
            $result = [
                'paging' => $orders,
                'trangthai' => $list
            ];
            $this->render('Order', $result, true);
        }

        function edit(){
            $order = Order::findByID($_POST['order_id']);
            $trangthai = OrderStatus::findByID($order->getIdTT());
            $details = OrderDetail::findByOrder($_POST['order_id']);
            $products = [];
            foreach($details as $item){
                $product = Product::findByID($item['idSach']);
                $products[] = $product->toArray();
            }
            $idNV = $order->getIdNV();
            $nhanvien = '';
            if($idNV != NULL) $nhanvien = (Account::findByID($idNV))->toArray();
            $khachhang = Account::findByID($order->getIdTK());
            $result = [
                'order' => $order->toArray(),
                'details' => $details,
                'nhanvien' => $nhanvien,
                'khachhang' => $khachhang->toArray(),
                'products' => $products,
                'trangthai' => $trangthai->toArray()
            ];
            echo json_encode($result);
            exit;
        }

        function update(){
            $ngaycapnhat = date("Y-m-d");
            session_start();
            $idNV = $_SESSION['admin']['idTK'];
            $idDH = $_POST['idDH'];
            $trangthai = $_POST['status-option'];
            $order = Order::findByID($idDH);
            $order->update($ngaycapnhat, $idNV, $trangthai);
            echo json_encode(array('success'=>true));
        }

        function search() {
            $pageTitle = 'searchOrder';
            $kyw = NULL;
            $status_select = NULL;
            $date_start = NULL;
            $date_end = NULL;

            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }

            if(isset($_GET['status_select']) && $_GET['status_select'] != -1 ) {
                $status_select = $_GET['status_select'];
                $pageTitle .= '&status_select='.$status_select;
            }

            if(isset($_GET['date_start']) && isset($_GET['date_end'])) {
                $date_start = $_GET['date_start'];
                $date_end = $_GET['date_end'];
                $pageTitle .= '&date_start='.$date_start.'&date_end='.$date_end;
            }
            $orders = Order::search($kyw, $status_select, $_GET['date_start'], $_GET['date_end']);
            $list = [];
            if($orders != NULL){
                foreach($orders as $order)
                    $list[] = OrderStatus::findByID($order->getIdTT());
            }
            $result = [
                'paging' => $orders,
                'trangthai' => $list
            ];

            $this->renderSearch('Order', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;
                case 'edit_data':
                    $this->edit();
                    break;

                case 'submit_btn_update':
                    $this->update();
                    break;

                case 'search':
                    $this->search();
                    break;
    
            }
        }
    }

    $orderController = new OrderController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchOrder') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $orderController->checkAction($action);

?>
