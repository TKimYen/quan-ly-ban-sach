<main>
    <!-- Quên mật khẩu - Nhập email để tìm mật khẩu -->
    <div class="container forgot-pw">
        <div class="forgot-pw-content">
            <div class="forgot-pw-content-box b-shadow">
                <div class="exclamation">
                    <i class="fa-thin fa-circle-exclamation"></i>
                </div>
                <div class="text">
                    <h4>Quên Mật Khẩu</h4>
                    <p>Vui lòng nhập vào địa chỉ email của bạn, chúng tôi sẽ gửi mã xác nhận giúp bạn khôi phục mật khẩu.</p>
                </div>
                <div class="form">
                    <form class="signin-form" id="form-forgotPassword" action="" method="POST">
                        <div class="input-email">
                            <i class="fa-thin fa-envelope"></i>
                            <input type="email" id="forgotPassword_email" name="email" placeholder="Nhập email...">
                            <span class="error errorMessage_forgotPassword_email" id="forgotPassword_error_email"></span>
                        </div>
                        <input type="hidden" name="action" value="submit_email">
                        <button class="btn submit-btn">
                            Gửi mã xác nhận
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
    <script src="asset/client/js/forgotPassword1.js?v=<?php echo time(); ?>"></script>