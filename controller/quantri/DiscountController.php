<?php
    include __DIR__.'/../BaseController.php';
    include __DIR__.'/../../model/Discount.php';

    class DiscountController extends BaseController{
        private $discount;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->discount = new Discount();
        }

        function index(){
            $discounts = Discount::getAll();
            $this->render('Discount', array('paging' => $discounts), true);
        }

        function add(){
            $this->discount->nhap($_POST['discount-percent'], $_POST['discount-date-start'], $_POST['discount-date-end']);
            $req = $this->discount->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false));
            exit;
        }

        function edit(){
            $discount = Discount::findByID($_POST['discount_id']);
            echo json_encode($discount==null ? null: $discount->toArray());
            exit;
        }

        function update(){
            $id = $_POST['discount_id'];
            $phantram = $_POST['discount-percent'];
            $ngaybatdau = $_POST['discount-date-start'];
            $ngayketthuc = $_POST['discount-date-end'];
            $trangthai = 'cdr';
            $this->discount->nhap($phantram, $ngaybatdau, $ngayketthuc, $trangthai, $id);
            $req = $this->discount->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false));
            exit;
        }

        function lock(){
            $this->discount->setIdMGG($_POST['discount_id']);
            $this->discount->setTrangthai('huy');
            $this->discount->lock();
            echo json_encode(array('success'=>true));
            exit;
        }

        function search(){
            $pageTitle = 'searchDiscount';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Discount::search($kyw)
            ];
            $this->renderSearch('Discount', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'submit_btn_add':
                    $this->add();
                    break;
                
                case 'edit_data':
                    $this->edit();
                    break;

                case 'submit_btn_update':
                    $this->update();
                    break;
                
                case 'lock_discount':
                    $this->lock();
                    break;

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $discountController = new DiscountController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchDiscount') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $discountController->checkAction($action);
?>