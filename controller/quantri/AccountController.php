<?php
    include dirname(__FILE__).'/../BaseController.php';
    include dirname(__FILE__).'/../../model/Account.php';
    include dirname(__FILE__).'/../../model/Role.php';

    class AccountController extends BaseController{
        private $account;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->account= new Account();
        }

        function index(){
            $accounts = Account::getAll();
            $roles = Role::getAll();
            $result = [
                'paging' => $accounts,
                'roles' => $roles
            ];
            $this->render('Account', $result, true);
        }

        function add(){
            $matkhau = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $this->account->nhap($_POST['username'], $_POST['userphone'], $_POST['usermail'], $matkhau, 1, $_POST['role-select']);
            $req = $this->account->add();
            if($req) echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false, 'msg'=>'Email đã tồn tại'));
            exit;
        }

        function openAddForm(){
            $role = Role::getAllActive();
            echo json_encode($role);
            exit;
        }

        function edit(){
            $account = Account::findByID($_POST['account_id']);
            $role = Role::getAllForAccount();
            $list = [];
            foreach($role as $item)
                $list[] = $item->toArrayNQ();
            if($account==null || empty($list)) $result = null;
            else
            $result = [
                'account' => $account->toArray(),
                'role' => $list
            ];
            echo json_encode($result);
            exit;
        }

        function update(){
            $trangthai = isset($_POST['status']) ? 1 : 0;
            $this->account->nhap($_POST['username'], $_POST['userphone'], $_POST['usermail'], NULL, $trangthai, $_POST['role-select'], $_POST['account_id']);
            $req = $this->account->update();
            if($req) echo json_encode(array('btn'=>'update','success'=>true));
            else echo json_encode(array('btn'=>'update','success'=>false, 'msg'=>'Email đã tồn tại'));
            exit;
        }

        function search(){
            $pageTitle = 'searchAccount';
            $kyw = NULL;
            $idNQ = NULL;
            $trangthai = NULL;

            if(isset($_GET['kyw']) && ($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }

            if(isset($_GET['select_role']) && $_GET['select_role'] != -1) {
                $idNQ = $_GET['select_role'];
                $pageTitle .= '&select_role='.$idNQ;
            }

            if(isset($_GET['status_select']) && $_GET['status_select'] != -1 ) {
                $trangthai = $_GET['status_select'];
                $pageTitle .= '&status_select='.$trangthai;
            }

            $roles = Role::getAll();

            $result = [
                'paging' => Account::search($kyw, $idNQ, $trangthai),
                'roles' => $roles
            ];
            $this->renderSearch('Account', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'open_add_form':
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

    $accountController = new AccountController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchAccount') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';
    else $action = $_POST['action'];
    $accountController->checkAction($action);
?>