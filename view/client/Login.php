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
                                <li>
                                    <strong>Mật khẩu<span class="mandatory-symbol">*</span></strong>
                                    <input class="signin-input" id="signIn_password" name="password" type="password" placeholder="Nhập mật khẩu..."><br>
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
                            <span>Chưa có tài khoản? &nbsp;	<a href="?page=signUp" class="nav-link">Đăng ký ngay</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="asset/client/js/signIn.js?v=<?php echo time(); ?>"></script>