<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__. '/../BaseController.php';
require __DIR__.'/../../model/Account.php';

    class ForgotPasswordController extends BaseController{
        private $account;

        function generateOTP() {
            $OTP = "";
            for ($i = 0; $i <6; $i++) {
                $OTP .= rand(0, 9);
            }
            //if (!isset($_SESSION['forgotPassword']['OTP'])) {
                $_SESSION['forgotPassword']['OTP'] = $OTP;
                $_SESSION['forgotPassword']['OTP_expiration'] = time() + (5 * 60); // Thời gian hết hạn sau 5 phút
            //}
        }

        function __construct()
        {
            $this->folder = 'quantri';
            $this->account = new Account();
        } 

        function show_emailInputForm(){
            $this->render('ForgotPassword','login');
        }

        function emailIsExist() {
            $msg = '';
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }
            $this->account = Account::findByEmail($_POST['email']);
            if ($this->account == null) $msg = 'Email không tồn tại';
            else if($this->account->getIdNQ() == 1) $msg = 'Tài khoản không đủ quyền truy cập';
            else if($this->account->getTrangthai() == 0) $msg = 'Tài khoản đã bị khóa';
            else $_SESSION['forgotPassword']['email'] = $_POST['email'];
            return $msg;
        }

        function sendOTPViaEmail() {
            require __DIR__.'/../../lib/PHPMailer/src/Exception.php';
            require __DIR__.'/../../lib/PHPMailer/src/PHPMailer.php';
            require __DIR__.'/../../lib/PHPMailer/src/SMTP.php';

            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }

            $OTP = $_SESSION['forgotPassword']['OTP'];

            $mail = new PHPMailer(true);

            $mail->isSMTP(); 
            $mail->SMTPAuth   = true;

            $mail->Host       = 'smtp.gmail.com';                                         
            $mail->Username   = 'doannhom4.pttkhttt@gmail.com';               
            $mail->Password   = 'ewvd stdf afap kckz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            
            $mail->Port       = 587; 

            $mail->setFrom('doannhom4.pttkhttt@gmail.com', 'Nha sach Vinabook');
            $mail->addAddress($_POST['email']);
            
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
            $this->render('AuthenticationCode','login');
        }

        function OTPisExpired() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }

            $current_time = time();

            if (isset($_SESSION['forgotPassword'])) {
                if ($current_time > $_SESSION['forgotPassword']['OTP_expiration']) {
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
            if ($_POST['OTP'] === $_SESSION['forgotPassword']['OTP']) {
                return true;
            } else {
                return false;
            }
        } 

        function show_changePasswordForm(){
            $this->render('ResetPassword');
        }

        function changePassword() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); 
            }

            $this->account = $this->account = Account::findByEmail($_SESSION['forgotPassword']['email']);
            $this->account->setMatkhau(password_hash($_POST['password'], PASSWORD_DEFAULT));
            $this->account->updateAccountPassword();

            unset($_SESSION['forgotPassword']);
        }

        function checkAction($action){
            switch ($action){
                case 'forgot_password':
                    $this->show_emailInputForm();
                    break;

                case 'submit_email':
                    $checkAccount = $this->emailisExist();
                    if ($checkAccount == '') {
                        $this->generateOTP();
                        $this->sendOTPViaEmail();
                        echo json_encode(['success' => true]);
                        exit;
                    } else {
                        echo json_encode(['success' => false, 'msg' => $checkAccount]);
                        exit;
                    }
                
                case 'authentication_code':
                    $this->show_OTPInputForm();
                    break;

                case 'submit_OTP':
                    if ($this->OTPisExpired() === false) {
                        if ($this->OTPIsMatched()) {
                            echo json_encode(['success' => true]);
                            exit;
                        } else {
                            echo json_encode(['success' => false, 'msg' => "Mã OTP không trùng khớp"]);
                            exit;
                        }
                    } else {
                        echo json_encode(['success' => false, 'msg' => "OTP Không còn hiệu lực"]);
                        exit;
                    }
        
                case 'reset_password':
                    $this->show_changePasswordForm();
                    break;

                case 'submit_changePassword':
                    $this->changePassword();
                    echo json_encode(['success' => true]);
                    exit;
            }
        }
    }

    $forgotPasswordController = new ForgotPasswordController();
    if(isset($_GET['page'])) {
        $action = $_GET['page'];
    } elseif(isset($_POST['action'])) {
        $action = $_POST['action'];
    }
    $forgotPasswordController->checkAction($action);
?>