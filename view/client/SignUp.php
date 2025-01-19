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
                            <li class="input-field">
                                <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signUp_password" name="password" type="password" placeholder="Nhập mật khẩu..."><br>
                                <span class="error errorMessage_signUp_password" id="signUp_error_password"></span>
                            </li>
                            <li class="input-field">
                                <strong>Xác nhận mật khẩu<span class="mandatory-symbol">*</span></strong>
                                <input class="signin-input" id="signUp_confirmPassword" name="confirmPassword" type="password" placeholder="Nhập xác nhận mật khẩu..."><br>
                                <span class="error errorMessage_signUp_confirmPassword" id="signUp_error_confirmPassword"></span>
                            </li>

                        </ul>
                        <div class="submit-btn">
                            <input type="hidden" name="action" value="submit_signUp">
                            <button class="btn btnDangKy" id="signUp_button">Đăng ký</input>
                        </div>
                    </form>
                    <div class="signin-text">
                        <span>Đã có tài khoản? &nbsp;	<a href="?page=login" class="nav-link">Đăng nhập ngay</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="asset/client/js/signUp.js?v=<?php echo time(); ?>"></script>