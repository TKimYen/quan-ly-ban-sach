<?php
include dirname(__FILE__).'/../BaseController.php';
include dirname(__FILE__).'/../../model/Author.php';

    class AuthorController extends BaseController{
        private $author;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->author= new Author();
        }

        function index(){
            $authors = Author::getAll();
            $this->render('Author', array('paging' => $authors), true);
        }

        function add(){
            $this->author->nhap($_POST['author_name'], $_POST['author_email'], 1);
            $req = $this->author->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false));
            exit;
        }

        function edit(){
            $author = Author::findByID($_POST['author_id']);
            echo json_encode($author==null ? null: $author->toArray());
            exit;
        }

        function update(){
            $idTG = $_POST['author_id'];
            $tenTG = $_POST['author_name'];
            $email = $_POST['author_email'];
            $trangthai = isset($_POST['status']) ? 1 : 0;
            $this->author->nhap($tenTG, $email, $trangthai,$idTG);
            $req = $this->author->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false));
            exit;
        }

        function search(){
            $pageTitle = 'searchAuthor';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Author::search($kyw)
            ];
            $this->renderSearch('Author', $result, $pageTitle);


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

    $authorController = new AuthorController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchAuthor') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $authorController->checkAction($action);

?>