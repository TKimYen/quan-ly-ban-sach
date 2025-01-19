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

    <!-- Link JS ở đây nè!!! -->
    <script src="../asset/quantri/js/Login.js"></script>