<?php
include __DIR__.'/../BaseController.php';
include __DIR__.'/../../model/Supplier.php';
    class SupplierController extends BaseController{
        private $supplier;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->supplier= new Supplier();
        }

        function index(){
            $suppliers = Supplier::getAll();
            $result = [
                'paging' => $suppliers
            ];
            $this->render('Supplier', $result, true);
        }

        function add(){
            $this->supplier->setTenNCC($_POST['supplier_name']);
            $this->supplier->setEmail($_POST['supplier_email']);
            $this->supplier->setDienthoai($_POST['supplier_phone']);
            // convert address to store
            $this->supplier->setDiachi($_POST['supplier_address'], $_POST['supplier_city'], $_POST['supplier_district'], $_POST['supplier_ward']);
            $this->supplier->setTrangthai(1);
            $req = $this->supplier->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false));
            exit;
        }

        function openEditForm(){
            $this->supplier = Supplier::findByID($_POST['supplier_id']);
            // convertToShow: return an array of id and sonha
            $supplierAddress = $this->supplier->convertToShow();
            $result = [];
            if($this->supplier != null){
                $city = City::getAll();
                $district = District::getAllByCity($supplierAddress['idTinh']);
                $ward = Ward::getAllByDistrict($supplierAddress['idQuan']);
                $result = [
                    'success' => true,
                    'supplier' => $this->supplier->toArray(),
                    'supplierAddress'=>$supplierAddress,
                    'city' => $city,
                    'district' => $district,
                    'ward' => $ward
                ];
            }
            else $result = [
                'success' => false,
                'msg' => 'Lỗi không tìm thấy dữ liệu'
            ];
            echo json_encode($result);
            exit;
        }

        function update(){
            $idNCC=$_POST['supplier_id'];
            $tenNCC = $_POST['supplier_name'];
            $email = $_POST['supplier_email'];
            $dienthoai = $_POST['supplier_phone'];
            $trangthai = isset($_POST['status']) ? 1 : 0;
            $this->supplier->nhap($idNCC, $tenNCC, '', $email, $dienthoai, $trangthai);
            $this->supplier->setDiachi($_POST['supplier_address'], $_POST['supplier_city'], $_POST['supplier_district'], $_POST['supplier_ward']);
            $req = $this->supplier->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false));
            exit;
        }

        function search() {
            $pageTitle = 'searchSupplier';
            $kyw = NULL;
            $status_select = NULL;
            $sort = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            if(isset($_GET['status_select']) && $_GET['status_select'] != -1 ) {
                $status_select = $_GET['status_select'];
                $pageTitle .= '&status_select='.$status_select;
            }
            if(isset($_GET['sort'])) {
                $sort = $_GET['sort'];
                $pageTitle .= '&sort='.$sort;
            }
            $result = [
                'paging' => Supplier::search($kyw, $status_select, $sort)
            ];
            $this->renderSearch('Supplier', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'submit_btn_add':
                    $this->add();
                    break;
                
                case 'open_edit_form':
                    $this->openEditForm();
                    break;
                // case 'edit_data':
                //     $this->edit();
                //     break;

                case 'submit_btn_update':
                    $this->update();
                    break;

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $supplierController = new SupplierController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchSupplier') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $supplierController->checkAction($action);
?>