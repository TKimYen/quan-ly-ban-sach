<main>
        <div class="result"></div>
        <div class="container signup">
            <div class="signin-content">
                <div class="signin-content-box b-shadow">
                    
                    <div class="title">
                        <span>Đăng Ký</span>
                    </div>
                    
                    <div class="signin-box">
                        <form class="signin-form" id="signUp_form">
                            <ul>
                                <li class="input-field">
                                    <strong>Họ và tên<span class="mandatory-symbol">*</span></strong>
                                    <input class="signin-input" id="signUp_fullname" name="fullname" type="text" placeholder="Nhập họ và tên...">
                                    <span class="error errorMessage_signUp_fullname" id="signUp_error_fullname"></span>
                                </li>

                                <li class="input-field">
                                    <strong>Email<span class="mandatory-symbol">*</span></strong>
                                    <input class="signin-input" id="signUp_email" name="email" type="email" placeholder="Nhập email...">
                                    <span class="error errorMessage_signUp_email" id="signUp_error_email"></span>
                                </li>

                                <li class="input-field">
                                    <strong>Số điện thoại<span class="mandatory-symbol">*</span></strong>
                                    <input class="signin-input" id="signUp_phoneNumber" name="phoneNumber" type="text" placeholder="Nhập số điện thoại...">
                                    <span class="error errorMessage_signUp_phoneNumber" id="signUp_error_phoneNumber"></span>
                                </li>

                                <!-- Ô nhập mật khẩu -->
                                <li class="input-field">
                                    <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong>
                                    <div class="password-wrapper">
                                        <input class="signin-input" id="signUp_password" name="password" type="password" placeholder="Nhập mật khẩu...">
                                        <span class="password-toggle" id="togglePassword1" onclick="togglePassword('signUp_password', 'eye1', 'eyeSlash1')">
                                            <i class="fas fa-eye" id="eye1"></i>
                                            <i class="fas fa-eye-slash" id="eyeSlash1" style="display: none;"></i>
                                        </span>
                                    </div>
                                    <span class="error errorMessage_signUp_password" id="signUp_error_password"></span>
                                </li>

                                <!-- Ô xác nhận mật khẩu -->
                                <li class="input-field">
                                    <strong>Xác nhận mật khẩu<span class="mandatory-symbol">*</span></strong>
                                    <div class="password-wrapper">
                                        <input class="signin-input" id="signUp_confirmPassword" name="confirmPassword" type="password" placeholder="Nhập xác nhận mật khẩu...">
                                        <span class="password-toggle" id="togglePassword2" onclick="togglePassword('signUp_confirmPassword', 'eye2', 'eyeSlash2')">
                                            <i class="fas fa-eye" id="eye2"></i>
                                            <i class="fas fa-eye-slash" id="eyeSlash2" style="display: none;"></i>
                                        </span>
                                    </div>
                                    <span class="error errorMessage_signUp_confirmPassword" id="signUp_error_confirmPassword"></span>
                                </li>

                            </ul>

                            <div class="submit-btn">
                                <input type="hidden" name="action" value="submit_signUp">
                                <button class="btn btnDangKy" id="signUp_button">Đăng ký</button>
                            </div>
                        </form>
                        <div class="signin-text">
                            <span>Đã có tài khoản? &nbsp; <a href="?page=login" class="nav-link">Đăng nhập ngay</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<script src="asset/client/js/signUp.js?v=<?php echo time(); ?>"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
.signup {
    height: 650px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.error {
    color: red;
}
.signin {
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.signin-content {
    display: flex;
    justify-content: center;
}

.signin-content-box {
    width: 560px;
    background-color: var(--white);
    padding: 20px;
    border-radius: 12px;
}

.signin-content-box .title {
    padding-bottom: 0px;
    border-bottom: 1px solid #333;
    margin-bottom: 25px;
}

.signin-content-box .title span {
    font-size: 22px;
    font-weight: 700;
    padding-bottom: 10px;
    display: flex;
    justify-content: center;
}

.signin-box {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.signin-form .input-field {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    width: 100%;
}

.signin-form .signin-input {
    flex-grow: 1;
    height: 32px;
    border-radius: 5px;
    border: 0.5px solid var(--border-cl);
    padding-left: 12px;
    font-size: 14px;
    color: #333;
    box-sizing: border-box;
}

.signin-form .password-wrapper,
.signin-form .confirm-password-wrapper {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
}

.signin-form .password-wrapper input,
.signin-form .confirm-password-wrapper input {
    flex-grow: 1;
    padding-right: 35px; /* Để không bị che bởi icon toggle */
}

.password-wrapper {
position: relative;
width: 100%;
display: flex;
align-items: center;
}

.password-toggle {
position: absolute;
top: 50%;
right: 10px;
transform: translateY(-50%);
cursor: pointer;
font-size: 18px;
color: gray;
}

.forgot-password {
    font-size: 13px;
    display: flex;
    justify-content: right;
    margin: 5px 0px;
}

.signin-box .signin-form button {
    background-color: var(--green);
    color: var(--white);
    border: 0.5px solid var(--green);
    border-radius: 5px;
    width: 120px;
    height: 30px;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.signin-box .signin-form button:hover {
    color: var(--green);
    background-color: var(--white);
}

.signin-box .signin-form .submit-btn {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

.signin-text span {
    display: flex;
    font-size: 14px;
    margin-top: 18px;
}

.signin-text span a {
    color: navy;
}
/* Placeholder full width fix */
.signin-form .confirm-password-wrapper input::placeholder {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}
</style>
<script>
    // Phần Toggle để xem mật khẩu
function togglePassword(inputId, eyeId, eyeSlashId) {
  const passwordInput = document.getElementById(inputId);
  const eyeIcon = document.getElementById(eyeId);
  const eyeSlashIcon = document.getElementById(eyeSlashId);

  if (passwordInput) {
      if (passwordInput.type === "password") {
          passwordInput.type = "text";
          eyeIcon.style.display = "none";
          eyeSlashIcon.style.display = "inline";
      } else {
          passwordInput.type = "password";
          eyeIcon.style.display = "inline";
          eyeSlashIcon.style.display = "none";
      }
  }
}
// Phần toggle để xem mật khẩu
</script>

