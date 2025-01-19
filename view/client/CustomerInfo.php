<main class="personal-page">
        <div class="container">
            <div class="row personal-page-content">
                <?php
                    include __DIR__.'/../../inc/client/personNavbar.php';
                ?>
                <div class="col-9">
                    <div class="info-personal-edit b-shadow">
                        <h4>Thông Tin Cá Nhân</h4>
                        <form action="" class="info-form" id="info_form" method="POST">
                            <fieldset>
                                <label for="full-name">Họ và Tên</label>
                                <input placeholder="<?php echo $_SESSION['user']['tenTK']?>" type="text" name="fullname" id="info_fullname">
                                <span class="error errorMessage_info_fullname" id="info_error_password"></span>
                            </fieldset>
                            <fieldset>
                                <label for="email">Email</label>
                                <input placeholder="<?php echo $_SESSION['user']['email']?>" type="text" name="email" id="info_email">
                                <span class="error errorMessage_info_email" id="info_error_email"></span>
                            </fieldset>
                            <fieldset>
                                <label for="phone-number">Số điện thoại</label>
                                <input placeholder="<?php echo $_SESSION['user']['dienthoai']?>" type="text" name="phoneNumber" id="info_phoneNumber">
                                <span class="error errorMessage_info_phoneNumber" id="info_error_phoneNumber"></span>
                            </fieldset>
                            <div class="save-changes">
                                <input type="hidden" name="action" value="submit_changeInfo">
                                <button class="btn btnConfirm" onclick="">
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