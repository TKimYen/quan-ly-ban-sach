    <!-- Content -->
    <img style="width: 300px; margin: 20px 0 0 20px;" src="../asset/quantri/img/vinabook-logo.png" alt="">
    <main class="container login-page">
        <div class="form-container p-4">
            <h2 class="form-title fw-bolder mb-4" style="color: #1D712C;">ĐĂNG NHẬP</h2>
            <form id="login-form">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="">
                    <label for="email">Email*</label>
                    <span class="errorMessage_email"></span>
                </div>
                <div class="form-floating mb-1">
                    <input type="password" class="form-control" id="password" name="password" placeholder="">
                    <label for="password">Password*</label>
                    <!-- Togle ẩn hiện -->
                    <span class="password-toggle" onclick="togglePassword('password', 'eyeIcon', 'eyeSlashIcon')">
                        <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="eyeIcon"></i>
                        <i class="fas fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" id="eyeSlashIcon" style="display: none;"></i>
                    </span>
                    <!-- hết togle ẩn hiện -->
                    <span class="errorMessage_password"></span>
                </div>
                <div class="forgot-password mb-4">
                    <a href="?page=forgot_password" class="forgot-password-link">Quên mật khẩu</a>
                </div>
                <input type="hidden" name="action" value="login">
                <button type="submit" class="btn col-12 btn-success text-white">Đăng nhập</button>
            </form>
        </div>
    </main>
    <style>

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
    <!-- Link JS ở đây nè!!! -->
    <script src="../asset/quantri/js/Login.js"></script>
    <script>
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
    </script>