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
                            <input placeholder="Nhập mật khẩu hiện tại..." type="password" name="currentPassword" id="info_currentPassword">
                            <span class="error errorMessage_info_currentPassword" id="info_error_currentPassword"></span>
                        </fieldset>
                        <fieldset>
                            <label for="password-new">Mật khẩu mới</label>
                            <input placeholder="Nhập mật khẩu mới..." type="password" name="newPassword" id="info_newPassword">
                            <span class="error errorMessage_info_newPassword" id="info_error_newPassword"></span>
                        </fieldset>
                        <fieldset>
                            <label for="password-new-repeat">Nhập lại mật khẩu</label>
                            <input placeholder="Nhập lại mật khẩu..." type="password" name="confirmNewPassword" id="info_confirmNewPassword">
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