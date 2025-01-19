    <!-- Content -->
    <img style="width: 300px; margin: 20px 0 0 20px;" src="../asset/img/vinabook-logo.png" alt="">
    <main class="container">
        <div class="form-container p-4">
            <div class="form-header d-flex flex-column justify-content-center align-items-center">
                <i class="fa-solid fa-lock mb-3" style="color: #1d712c; font-size: 80px;"></i>
                <h2 class="form-title fw-bolder mb-3" style="color: #1D712C;">MẬT KHẨU MỚI</h2>
                <!-- <p class="form-subtitle mb-3 text-center fw-light fs-6">Vui lòng nhập vào mã xác thực đã được gửi qua email của bạn.</p> -->
            </div>
            <form action="" id="form-passwordRecovery" method="POST">
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control" id="passwordRecovery_password" placeholder="">
                    <label for="password">Mật khẩu mới*</label> <br>
                    <span class="error errorMessage_forgotPassword_password" id="forgotPassword_error_OTP"></span>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" name="confirmPassword" class="form-control" id="passwordRecovery_confirmPassword" placeholder="">
                    <label for="password-confirm">Xác nhận mật khẩu*</label> <br>
                    <span class="error errorMessage_forgotPassword_confirmPassword" id="forgotPassword_error_OTP"></span>
                </div>
                <input type="hidden" name="action" value="submit_changePassword">
                <button type="submit" class="btn col-12 btn-success text-white">Đổi mật khẩu</button>
            </form>
        </div>
    </main>

    <script src="../asset/quantri/js/ResetPassword.js?v=<?php echo time(); ?>"></script>