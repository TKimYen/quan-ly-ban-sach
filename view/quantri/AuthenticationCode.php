    <!-- Content -->
    <img style="width: 300px; margin: 20px 0 0 20px;" src="../asset/quantri/img/vinabook-logo.png" alt="">
    <main class="container">
        <div class="form-container p-4">
            <div class="form-header d-flex flex-column justify-content-center align-items-center">
                <i class="fa-solid fa-lock-hashtag mb-3" style="color: #1d712c; font-size: 80px;"></i>
                <h2 class="form-title fw-bolder mb-1" style="color: #1D712C;">NHẬP MÃ XÁC THỰC</h2>
                <p class="form-subtitle mb-3 text-center fw-light fs-6">Vui lòng nhập vào mã xác thực đã được gửi qua email của bạn.</p>
            </div>
            <form action="" id="form-OTPInput" method="POST">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="forgotPassword_OTP" name="OTP" placeholder="">
                    <span class="error errorMessage_forgotPassword_OTP" id="forgotPassword_error_OTP"></span>
                    <label for="token">Mã xác thực*</label>
                </div>
                <input type="hidden" name="action" value="submit_OTP">
                <button type="submit" class="btn col-12 btn-success text-white">Xác nhận</button>
            </form>
        </div>
    </main>

    <script src="../asset/quantri/js/AuthenticationCode.js?v=<?php echo time(); ?>"></script>