<main class="personal-page">
    <div class="container changePassword">
        <div class="row personal-page-content">
            <?php
                include __DIR__.'/../../inc/client/personNavbar.php';
            ?>
            <div class="col-9">
                <div class="info-personal-edit b-shadow">
                    <h4>Thay đổi mật khẩu</h4>
                    <form class="info-form" id="changePassword_form" method="POST">
                    <fieldset>
                        <label for="password-current">Mật khẩu hiện tại</label>
                        <div class="password-wrapper">
                            <input placeholder="Nhập mật khẩu hiện tại..." type="password" name="currentPassword" id="info_currentPassword">
                            <span class="password-toggle" onclick="togglePassword('info_currentPassword', 'eye1', 'eyeSlash1')">
                                <i class="fas fa-eye" id="eye1"></i>
                                <i class="fas fa-eye-slash" id="eyeSlash1" style="display: none;"></i>
                            </span>
                        </div>
                        <span class="error errorMessage_info_currentPassword" id="info_error_currentPassword"></span>
                    </fieldset>

                    <fieldset>
                        <label for="password-new">Mật khẩu mới</label>
                        <div class="password-wrapper">
                            <input placeholder="Nhập mật khẩu mới..." type="password" name="newPassword" id="info_newPassword">
                            <span class="password-toggle" onclick="togglePassword('info_newPassword', 'eye2', 'eyeSlash2')">
                                <i class="fas fa-eye" id="eye2"></i>
                                <i class="fas fa-eye-slash" id="eyeSlash2" style="display: none;"></i>
                            </span>
                        </div>
                        <span class="error errorMessage_info_newPassword" id="info_error_newPassword"></span>
                    </fieldset>

                    <fieldset>
                        <label for="password-new-repeat">Nhập lại mật khẩu</label>
                        <div class="password-wrapper">
                            <input placeholder="Nhập lại mật khẩu..." type="password" name="confirmNewPassword" id="info_confirmNewPassword">
                            <span class="password-toggle" onclick="togglePassword('info_confirmNewPassword', 'eye3', 'eyeSlash3')">
                                <i class="fas fa-eye" id="eye3"></i>
                                <i class="fas fa-eye-slash" id="eyeSlash3" style="display: none;"></i>
                            </span>
                        </div>
                        <span class="error errorMessage_info_confirmNewPassword" id="info_error_confirmNewPassword"></span>
                    </fieldset>
                        <div class="save-changes">
                            <input type="hidden" name="action" value="submit_changePassword">
                            <button class="btn">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="asset/client/js/customerInfo.js?v=<?php echo time(); ?>"></script>
<style>
.password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-wrapper input {
    flex-grow: 1;
    padding-right: 40px; /* Để không bị che bởi icon */
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    color: #555;
}

</style>
<script>
    function togglePassword(inputId, eyeId, eyeSlashId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(eyeId);
        const eyeSlashIcon = document.getElementById(eyeSlashId);

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
</script>
