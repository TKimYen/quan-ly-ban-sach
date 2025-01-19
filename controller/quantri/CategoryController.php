<?php
    include dirname(__FILE__).'/../BaseController.php';
    include dirname(__FILE__).'/../../model/Category.php';
    class CategoryController extends BaseController{
        private $category;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->category = new Category();
        }

        function index(){
            $categories = Category::getAll();
            $result = [
                'paging' => $categories
            ];
            $this->render('Category', $result, true);
        }

        function add(){
            $this->category->nhap($_POST['category_name'], 1);
            $req = $this->category->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false));
            exit;
        }

        function edit(){
            $category = Category::findByID($_POST['category_id']);
            echo json_encode($category==null ? null: $category->toArray());
            exit;
        }

        function update(){
            $idTL = $_POST['category_id'];
            $tenTL = $_POST['category_name'];
            $trangthai = isset($_POST['status']) ? 1 : 0;
            $this->category->nhap($tenTL, $trangthai, $idTL);
            $req = $this->category->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false));
            exit;
        }

        function search(){
            $pageTitle = 'searchCategory';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Category::search($kyw)
            ];
            $this->renderSearch('Category', $result, $pageTitle);
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

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $categoryController = new CategoryController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchCategory') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $categoryController->checkAction($action);
?>