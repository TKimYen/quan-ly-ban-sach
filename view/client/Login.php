<main>
    <!-- Đăng nhập -->
    <div class="container signin">
        <div class="signin-content">
            <div class="signin-content-box b-shadow">
                <!-- title đăng nhập -->
                <div class="title">
                    <span>Đăng Nhập</span>
                </div>
                <!-- form điền email, mật khẩu để đăng nhập -->
                <div class="signin-box">
                    <form class="signin-form" id="signIn_form">
                        <ul>
                            <li class="input-field">
                                <strong>Email<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signIn_email" name="email" type="text" placeholder="Nhập email..."><br>
                                <span class="error errorMessage_signIn_email" id="signIn_error_email"></span>
                            </li>

                            <!-- Ô nhập mật khẩu -->
                            <li class="input-field">
                                <div class="label-container">
                                    <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong>
                                </div>
                                <!-- <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong> -->
                                <div class="password-wrapper">
                                    <input class="signin-input" id="signIn_password" name="password" type="password" placeholder="Nhập mật khẩu...">
                                    <span class="password-toggle" id="togglePassword">
                                        <i class="fas fa-eye" id="eye"></i>
                                        <i class="fas fa-eye-slash" id="eye-slash" style="display: none;"></i>
                                    </span>
                                </div><br>
                                <span class="error errorMessage_signIn_password" id="signIn_error_password"></span>
                            </li>
                        </ul>
                        <div class="forgot-password">
                            <a class="nav-link" href="?page=forgotPassword"><i>Quên mật khẩu?</i></a>
                        </div>
                        <div class="submit-btn">
                            <input type="hidden" name="action" value="submit_login">
                            <button class="btn btnSignIn">Đăng nhập</button>
                        </div>
                    </form>
                    <div class="signin-text">
                        <span>Chưa có tài khoản? &nbsp; <a href="?page=signUp" class="nav-link">Đăng ký ngay</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="asset/client/js/signIn.js?v=<?php echo time(); ?>"></script>
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
        box-sizing: border-box;
        /* Đảm bảo padding không làm thay đổi kích thước */
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
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById("signIn_password");
        const togglePassword = document.getElementById("togglePassword");
        const eyeIcon = document.getElementById("eye");
        const eyeSlashIcon = document.getElementById("eye-slash");

        togglePassword.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.style.display = "none";
                eyeSlashIcon.style.display = "inline";
            } else {
                passwordInput.type = "password";
                eyeIcon.style.display = "inline";
                eyeSlashIcon.style.display = "none";
            }
        });
    });
</script>