<main>
    <!-- Quên mật khẩu - Nhập mã xác nhận đã được gửi qua email -->
    <div class="container forgot-pw">
        <div class="forgot-pw-content">
            <div class="forgot-pw-content-box b-shadow">
                <div class="exclamation">
                    <i class="fa-thin fa-circle-exclamation"></i>
                </div>
                <div class="text">
                    <h4>Quên Mật Khẩu</h4>
                    <p>Vui lòng nhập mã xác nhận đã được gửi qua email của bạn.</p>
                </div>
                <div class="form">
                    <form action="" class="signin-form" id="form-OTPInput" method="POST">
                        <div class="input-email">
                            <i class="fa-thin fa-envelope"></i>
                            <input type="text" id="forgotPassword_OTP" name="OTP" placeholder="Nhập mã xác nhận...">
                        </div>
                        <span class="error errorMessage_forgotPassword_OTP" id="forgotPassword_error_OTP"></span>
                        <input type="hidden" name="action" value="submit_OTP">
                        <button class="btn submit-btn">
                            Xác nhận
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
    <script src="asset/client/js/forgotPassword2.js?v=<?php echo time(); ?>"></script>