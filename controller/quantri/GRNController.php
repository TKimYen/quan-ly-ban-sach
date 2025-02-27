<?php
    include __DIR__.'/../BaseController.php';
    include __DIR__.'/../../model/GRN.php';
    include __DIR__.'/../../model/Supplier.php';
    include __DIR__.'/../../model/Product.php';
    include __DIR__.'/../../model/GRNDetail.php';
    include __DIR__.'/../../model/Account.php';

    class GRNController extends BaseController{
        private GRN $grn;
        private GRNDetail $detail;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->grn = new Grn();
            $this->detail = new GRNDetail();
        }

        function index(){
            $grn = GRN::getAll();
            $supplier = Supplier::getAllActive_GRN();
            $result = [
                'paging' => $grn,
                'supplier' => $supplier
            ];
            $this->render('GoodsReceiveNote', $result, true);
        }

        function openAddForm(){
            $product = Product::getAllBySupplier($_POST['supplier_id']);
            echo json_encode($product==null ? null: $product);
        }

        function add(){
            $ngaytao = $_POST['ngaytao'];
            $ngaycapnhat = $_POST['ngaycapnhat'];
            $chietkhau = $_POST['chietkhau'];
            $idNV = $_POST['idNV'];
            // tao moi phieu nhap kho
            $this->grn->setIdNV($idNV);
            $this->grn->addNewphieunhapkho();
            $idPN = GRN::getLastPhieuNhapKhoID();
            $this->grn->setIdPN($idPN);
            // so san pham
            $n = count($_POST['grn_product']);
            $tongtien = $_POST['tongtien'];
            $tongsoluong = 0;
            $error = false;
            $errorCount = 0;
            for($i=1; $i<=$n; $i++){
                $idSach = $_POST['grn_product'][$i-1];
                $soluong = $_POST['grn_quantity'][$i];
                $tongsoluong+=$soluong;
                $this->detail->nhap($idPN, $idSach, $soluong);
                try{
                    $this->detail->addCTPhieuNhap();
                } catch(Exception $e){
                    $this->grn->deletePhieuNhap();
                    $error = true;
                    $errorCount = $i;
                    break;
                }
            }
            if(!$error){
                $this->grn->nhapUpdate($ngaycapnhat, $tongsoluong, $tongtien, "cht", $ngaytao, $chietkhau);
                $this->grn->createPhieuNhapKho();
                echo json_encode(array('success'=>true));
            } else {
                echo json_encode(array(
                    'success'=>false,
                    'msg' => 'Sản phẩm bị trùng. Lỗi dòng '.$errorCount
                ));
            }
        
        }


        function edit(){
            $this->grn = GRN::findByID($_POST['grn_id']);
            $details = GRNDetail::findByGRN($_POST['grn_id']);
            $products = [];
            foreach($details as $item){
                $product = Product::findByID($item['idSach']);
                $products[] = $product->toArray();
            }
            $supplier = Supplier::findByIdSach($details[0]['idSach']);
            $nhanvien = Account::findByID($this->grn->getIdNV());
            $result = [
                'grn' => $this->grn->toArray(),
                'details' => $details,
                'supplier' => $supplier->toArray(),
                'nhanvien' => $nhanvien->toArray(),
                'products' => $products
            ];
            echo json_encode($result);
            exit;
        }


        function getBaseInfo(){
            session_start();
            $result = [
                'idTK' => $_SESSION['admin']['idTK'],
                'tenTK' => $_SESSION['admin']['tenTK'],
                'currentdate' => date("Y-m-d")
            ];
            echo json_encode($result);
        }


        function update(){
            $ngaycapnhat = date("Y-m-d");
            session_start();
            $idNV = $_SESSION['admin']['idTK'];
            $idPN = $_POST['idPN'];
            $trangthai = $_POST['status'];
            $this->grn->setIdPN($idPN);
            // so san pham
            $n = count($_POST['grn_product']);
            $tongtien = $_POST['tongtien'];
            $tongsoluong = 0;
            for($i=1; $i<=$n; $i++){
                $idSach = $_POST['grn_product'][$i-1];
                $soluong = $_POST['grn_quantity'][$i];
                $tongsoluong+=$soluong;
                $this->detail->nhap($idPN, $idSach, $soluong);
                $this->detail->updateCTPhieuNhap();
            }
                $this->grn->nhapUpdate($ngaycapnhat, $tongsoluong, $tongtien, $trangthai);
                $this->grn->update();
                echo json_encode(array('success'=>true));
        }

        function search() {
            $pageTitle = 'searchGRN';
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


            $result = [
                'paging' => GRN::search($kyw, $status_select, $_GET['date_start'], $_GET['date_end'])
            ];

            $this->renderSearch('GoodsReceiveNote', $result, $pageTitle);
        }



        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'getBaseInfo':
                    $this->getBaseInfo();
                    break;

                case 'openAddForm':
                    $this->openAddForm();
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

                case 'search':
                    $this->search();
                    break;
    
            }
        }
    }


$grnController = new GRNController();
if(isset($_GET['page']) && $_GET['page'] == 'searchGRN') $action = 'search';
else if (!isset($_POST['action'])) $action = 'index';
else $action = $_POST['action'];
$grnController->checkAction($action);
?>

