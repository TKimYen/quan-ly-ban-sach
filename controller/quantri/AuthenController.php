<?php
include __DIR__.'/../BaseController.php';
include __DIR__.'/../../model/Account.php';
include __DIR__.'/../../model/Role.php';

    class AuthenController extends BaseController{
        private $account;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->account = new Account();
        }

        function show_loginForm(){
            $this->render('Login');
        }

        function login(){
            // kiem tra thong tin dang nhap
            $email = $_POST['email'];
            $password = $_POST['password'];
            $this->account = Account::findByEmail($email);
            $msg = $this->checkLogin($email, $password);
            if($msg==''){
                $role = Role::findByID($this->account->getIdNQ());
                session_start();
                $_SESSION['admin'] = $this->account->toArray();
                $_SESSION['permission'] = $role->toArrayDetail();
                foreach($_SESSION['permission'] as $item)
                    $_SESSION['function'][$item['tenCN']] = true;
                
                echo json_encode(array('success'=>true));
            }
            else echo json_encode(array('success'=>false, 'msg'=>$msg));
            exit;
        }

        function checkLogin($email, $password){
            $msg = '';
            // Kiểm tra xem có tồn tại không?
            if(!empty($this->account)) {
                if($this->account->getIdNQ()!=1){
                    $db_password = $this->account->getMatkhau();
                    // So sánh mật khẩu nhập vào với mật khẩu trong cơ sở dữ liệu
                    if (password_verify($password, $db_password)) {
                        if ($this->account->getTrangthai() != 1) 
                            $msg = "Tài khoản của bạn đã bị khoá!";
                    }else $msg = "Mật khẩu không chính xác!";
                }else $msg = "Tài khoản không có quyền truy cập!";
            }else $msg = "Tài khoản không tồn tại!";
            return $msg;
        }    

        function checkAction($action){
            switch ($action){
                case 'show_loginForm':
                    $this->show_loginForm();
                    break;

                case 'login':
                    $this->login();
                    break;
            }
        }
    }

    $authenController = new AuthenController();
    if(!isset($_POST['action'])){
        $action = 'show_loginForm';
    }
    else{
        $action = $_POST['action'];
    }
    $authenController->checkAction($action);
?>