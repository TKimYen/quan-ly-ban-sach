<main>
    <!-- Đăng nhập -->
    <div class="result"></div>
    <div class="container signup">
        <div class="signin-content">
            <div class="signin-content-box b-shadow">
                <!-- title đăng ký -->
                <div class="title">
                    <span>Đăng Ký</span>
                </div>
                <!-- form điền thông tin tạo tài khoản -->
                <div class="signin-box">
                    <form class="signin-form" id="signUp_form">
                        <ul>
                            <li class="input-field">
                                <strong>Họ và tên<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signUp_fullname" name="fullname" type="text" placeholder="Nhập họ và tên..."><br>
                                <span class="error errorMessage_signUp_fullname" id="signUp_error_fullname"></span>
                            </li>
                            <li class="input-field">
                                <strong>Email<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signUp_email" name="email" type="email" placeholder="Nhập email..."><br>
                                <span class="error errorMessage_signUp_email" id="signUp_error_email"></span>
                            </li>
                            <li class="input-field">
                                <strong>Số điện thoại<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signUp_phoneNumber" name="phoneNumber" type="text" placeholder="Nhập số điện thoại..."><br>
                                <span class="error errorMessage_signUp_phoneNumber" id="signUp_error_phoneNumber"></span>
                            </li>
                            <!-- Ô nhập mật khẩu -->
                            <li class="input-field">
                                <div class="label-container">
                                    <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong>
                                </div>
                                <!-- <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong> -->
                                <div class="password-wrapper">
                                    <input class="signin-input" id="signUp_password" name="password" type="password" placeholder="Nhập mật khẩu...">
                                    <span class="password-toggle" id="togglePassword1" onclick="togglePassword('signUp_password', 'eye1', 'eyeSlash1')">
                                        <i class="fas fa-eye" id="eye1"></i>
                                        <i class="fas fa-eye-slash" id="eyeSlash1" style="display: none;"></i>
                                    </span>
                                </div><br>
                                <span class="error errorMessage_signUp_password" id="signUp_error_password"></span>
                            </li>

                            <!-- Ô xác nhận mật khẩu -->
                            <li class="input-field">
                                <div class="label-container">
                                    <strong>Xác nhận mật khẩu<span class="mandatory-symbol">*</span></strong>
                                </div>
                                <!-- <strong>Xác nhận mật khẩu<span class="mandatory-symbol">*</span></strong> -->
                                <div class="password-wrapper">
                                    <input class="signin-input" id="signUp_confirmPassword" name="confirmPassword" type="password" placeholder="Nhập xác nhận mật khẩu...">
                                    <span class="password-toggle" id="togglePassword2" onclick="togglePassword('signUp_confirmPassword', 'eye2', 'eyeSlash2')">
                                        <i class="fas fa-eye" id="eye2"></i>
                                        <i class="fas fa-eye-slash" id="eyeSlash2" style="display: none;"></i>
                                    </span>
                                </div><br>
                                <span class="error errorMessage_signUp_confirmPassword" id="signUp_error_confirmPassword"></span>
                            </li>
                        </ul>
                        <div class="submit-btn">
                            <input type="hidden" name="action" value="submit_signUp">
                            <button class="btn btnDangKy" id="signUp_button">Đăng ký</input>
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

<style>
.input-field {
    display: grid;
    grid-template-columns: 150px 1fr;
    align-items: center;
    width: 100%;
    margin-bottom: 0px;
}

.input-field input {
    width: 100%;
    box-sizing: border-box; /* Đảm bảo padding không làm thay đổi kích thước */
}

.label-container {
    display: flex;
    align-items: center;
    white-space: nowrap;
}

    .password-wrapper input,
    .confirm-password-wrapper input {
        flex-grow: 1;
        padding-right: 35px;
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