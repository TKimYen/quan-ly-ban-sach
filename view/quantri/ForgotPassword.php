    <!-- Content -->
    <img style="width: 300px; margin: 20px 0 0 20px;" src="../asset/quantri/img/vinabook-logo.png" alt="">
    <main class="container">
        <div class="form-container p-4">
            <div class="form-header d-flex flex-column justify-content-center align-items-center">
                <i class="fa-solid fa-circle-exclamation mb-3" style="color: #1d712c; font-size: 80px;"></i>
                <h2 class="form-title fw-bolder mb-1" style="color: #1D712C;">QUÊN MẬT KHẨU</h2>
                <p class="form-subtitle mb-4 text-center fw-light fs-6">Vui lòng nhập vào địa chỉ email của bạn, chúng tôi 
                sẽ gửi mã xác nhận nhằm giúp bạn khôi phục mật khẩu.</p>
            </div>
            <form action="" id="forgot_password_form" method="POST">
                <div class="form-floating mb-4">
                    <input type="email" class="form-control" id="forgotPassword_email" name="email" placeholder=""><br>
                    <span class="error errorMessage_forgotPassword_email" id="forgotPassword_error_email"></span>
                    <label for="email">Email tài khoản*</label>
                </div>
                <input type="hidden" name="action" value="submit_email">
                <button type="submit" class="btn col-12 btn-success text-white">Gửi mã xác nhận</button>
            </form>
        </div>
    </main>

    <script src="../asset/quantri/js/ForgotPassword.js?v=<?php echo time(); ?>"></script>