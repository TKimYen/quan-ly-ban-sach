<main>
    <!-- Quên mật khẩu - Nhập mã xác nhận đã được gửi qua email -->
    <div class="container forgot-pw">
        <div class="forgot-pw-content">
            <div class="forgot-pw-content-box b-shadow">
                <div class="exclamation">
                    <i class="fa-thin fa-circle-exclamation"></i>
                </div>
                <div class="text">
                    <h4>Chúng tôi cần xác thực email của bạn</h4>
                    <p>Vui lòng nhập mã xác nhận đã được gửi qua email của bạn.</p>
                </div>
                <div class="form">
                    <form action="" class="signin-form" id="form-OTPInput" method="POST">
                        <div class="input-email">
                            <i class="fa-thin fa-envelope"></i>
                            <input type="text" id="signUp_OTP" name="OTP" placeholder="Nhập mã xác nhận...">
                        </div>
                        <span class="error errorMessage_signUp_OTP" id="forgotPassword_error_OTP"></span>
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
    <script src="asset/client/js/signUp_OTP.js?v=<?php echo time(); ?>"></script>