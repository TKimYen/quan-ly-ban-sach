<main>
    <div class="container signin">
        <div class="signin-content">
            <div class="signin-content-box b-shadow">
                <div class="title">
                    <span>Đăng Nhập</span>
                </div>
                <div class="signin-box">
                    <form class="signin-form" id="signIn_form">
                        <ul>
                            <li class="input-field">
                                <strong>Email<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signIn_email" name="email" type="text" placeholder="Nhập email..."><br>
                                <span class="error errorMessage_signIn_email" id="signIn_error_email"></span>
                            </li>
                            <li class="input-field">
                                <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong>
                                <div class="password-wrapper">
                                    <input class="signin-input" id="signIn_password" name="password" type="password" placeholder="Nhập mật khẩu...">
                                    <span class="password-toggle" id="togglePassword">
                                        <i class="fas fa-eye" id="eye"></i>
                                        <i class="fas fa-eye-slash" id="eye-slash" style="display: none;"></i>
                                    </span>
                                </div>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .signin-form .input-field {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        height: auto; /* Chiều cao tự động */
    }

    .signin-form strong {
        font-weight: 400;
        width: auto;
        font-size: 14px;
        margin-right: 10px;
    }

    .password-wrapper {
        position: relative;
        flex-grow: 1;
    }

    .signin-form .signin-input {
        width: 100%;
        height: 32px;
        border-radius: 5px;
        border: 0.5px solid var(--border-cl);
        padding-left: 12px;
        font-size: 14px;
        color: #333;
        box-sizing: border-box;
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

    .signin-content-box {
        width: 560px; /* Hoặc min-width: 560px; */
        /* ... Các style khác ... */
    }

    /* ... Các style khác của bạn ... */
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("signIn_password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeIcon = document.getElementById("eye");
    const eyeSlashIcon = document.getElementById("eye-slash");

    togglePassword.addEventListener("click", function () {
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