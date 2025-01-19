<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__. '/../BaseController.php';
require __DIR__.'/../../model/Account.php';

    class AuthenController extends BaseController{
        private $account;

        function __construct()
        {
            $this->folder = 'client';
            $this->account = new Account();
        } 

        function show_loginForm(){
            $this->render('login');
        }

        function login(){
            // kiem tra thong tin dang nhap
            $email = $_POST['email'];
            $password = $_POST['password'];
            $this->account = Account::findByEmail($email);
            $msg = $this->checkLogin($email, $password);
            if($msg==''){
                //$role = Role::findByID($this->account->getIdNQ());
                if (session_status() == PHP_SESSION_NONE) { 
                    session_start(); 
                }
                $_SESSION['user'] = $this->account->toArray();
                echo json_encode(array('success'=>true));
            }
            else echo json_encode(array('success'=>false, 'msg'=>$msg));
            exit;
        }

        function checkLogin($email, $password){
            $msg = '';
            // Kiểm tra xem có tồn tại không?
            if(!empty($this->account)) {
                if($this->account->getIdNQ() == 1){
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

        function show_signUpForm(){
            $this->render('signUp');
        }

        function storeInfoResult(){
            // kiem tra thong tin dang nhap

            $tenTK = $_POST['fullname'];
            $email = $_POST['email'];
            $dienthoai = $_POST['phoneNumber'];
            $matkhau = $_POST['password'];
            $checkEmail = Account::findByEmail($email);
            if($checkEmail === null) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start(); 
                }

                $_SESSION['signUp']['tenTK'] = $tenTK;
                $_SESSION['signUp']['email'] = $email;
                $_SESSION['signUp']['dienthoai'] = $dienthoai;
                $_SESSION['signUp']['matkhau'] = $matkhau;

                return true;
            } else  {
                return false;
            }
        }

        function generateOTP() {
            $OTP = "";
            for ($i = 0; $i <6; $i++) {
                $OTP .= rand(0, 9);
            }
            // if (!isset($_SESSION['forgotPassword']['OTP'])) {
                $_SESSION['signUp']['OTP'] = $OTP;
                $_SESSION['signUp']['OTP_expiration'] = time() + (5 * 60); // Thời gian hết hạn sau 5 phút
            // }
        }

        function sendOTPViaEmail() {
            require __DIR__.'/../../lib/PHPMailer/src/Exception.php';
            require __DIR__.'/../../lib/PHPMailer/src/PHPMailer.php';
            require __DIR__.'/../../lib/PHPMailer/src/SMTP.php';

            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }

            $OTP = $_SESSION['signUp']['OTP'];

            $mail = new PHPMailer(true);

            $mail->isSMTP(); 
            $mail->SMTPAuth   = true;

            $mail->Host       = 'smtp.gmail.com';                                         
            $mail->Username   = 'doannhom4.pttkhttt@gmail.com';               
            $mail->Password   = 'ewvd stdf afap kckz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            
            $mail->Port       = 587; 

            $mail->setFrom('doannhom4.pttkhttt@gmail.com', 'Nha sach Vinabook');
            $mail->addAddress($_SESSION['signUp']['email']);
            
            $mail->isHTML(true);
            $mail->Subject = "Your Vinabook account verification code";

            $email_template = "
                <h2>Xin chào bạn</h2>
                <h3>Đây là mã xác nhận cho tài khoản Vinabook của bạn, tuyệt đối không chia sẻ cho bất kì ai: {$OTP}</h3>
                <h3>Lưu ý: Mã xác nhận này sẽ hết hạn sau 5 phút</h3>
            ";
            $mail->Body = $email_template;
            $mail->send();
        }
    
        function show_OTPInputForm(){
            $this->render('signUp_OTP');
        }

        function OTPisExpired() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }

            $current_time = time();

            if (isset($_SESSION['signUp'])) {
                if ($current_time > $_SESSION['signUp']['OTP_expiration']) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        function OTPIsMatched() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }
            if ($_POST['OTP'] === $_SESSION['signUp']['OTP']) {
                return true;
            } 
            return false;
        } 

        function signUp(){
            // kiem tra thong tin dang nhap

            $tenTK = $_SESSION['signUp']['tenTK'];
            $email = $_SESSION['signUp']['email'];
            $dienthoai = $_SESSION['signUp']['dienthoai'];
            $matkhau = $_SESSION['signUp']['matkhau'];

            $this->account->setTenTK($tenTK);
            $this->account->setEmail($email);
            $this->account->setDienthoai($dienthoai);
            $this->account->setMatkhau(password_hash($matkhau  , PASSWORD_DEFAULT));
            $this->account->setTrangthai("1");
            $this->account->setIdNQ("1");
            $this->account->add();

            unset($_SESSION['signUp']);

            echo json_encode(array('success'=>true, 'msg' => "Đăng ký tài khoản thành công"));
            exit;
        }

        function checkAction($action){
            switch ($action){
                case 'login':
                    $this->show_loginForm();
                    break;

                case 'signUp':
                    $this->show_signUpForm();
                    break;
                
                case 'submit_login':
                    $this->login();
                    break;

                case 'submit_signUp':
                    $result = $this->storeInfoResult();
                    if ($result === true) {
                        $this->generateOTP();
                        $this->sendOTPViaEmail();
                        echo json_encode(['success'=> true]);
                    } else {
                        echo json_encode(['success' => false, 'msg' => 'Email đã tồn tại']);
                        exit;
                    }
                    break;

                case 'signUp_OTP':
                    $this->show_OTPInputForm();
                    break;

                case 'submit_OTP':
                    if ($this->OTPisExpired() === false) {
                        if ($this->OTPIsMatched()) {
                            $this->signUp();
                            echo json_encode(['success' => true, 'msg' => "Mã OTP không trùng khớp"]);
                            exit;
                        } else {
                            echo json_encode(['success' => false, 'msg' => "Mã OTP không trùng khớp"]);
                            exit;
                        }
                    } else {
                        echo json_encode(['success' => false, 'msg' => "OTP Không còn hiệu lực"]);
                        exit;
                    }
            }
        }
    }
    $authenController = new AuthenController();
    if(isset($_GET['page'])) $action = $_GET['page'];
    // else $action = $_GET['page'];
    if(isset($_POST['action'])) $action = $_POST['action'];
    $authenController->checkAction($action);
?>