<main class="checkout-submit-box">
    <div class="box-container p-4">
        <?php
        if ((isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] == '00') || $_GET['payment_method'] == 'cod') {
            echo '
            <div class="box-header d-flex flex-column justify-content-center align-items-center gap-3">
                <i class="fa-solid fa-circle-check box-container-icon checkout-success"></i>
                <h2 class="box-title fw-bolder checkout-success">THANH TOÁN THÀNH CÔNG</h2>
            </div>
            <p class="box-subtitle mb-3 text-center fw-light fs-6">Cảm ơn bạn đã mua hàng tại Vinabook. Đơn hàng của bạn sẽ được xử lý trong thời gian sớm nhất.</p>
            ';
        } else {
            echo '
            <div class="box-header d-flex flex-column justify-content-center align-items-center gap-3 mb-3">
                <i class="fa-solid fa-circle-xmark box-container-icon checkout-fail"></i>
                <h2 class="box-title fw-bolder checkout-fail">THANH TOÁN THẤT BẠI</h2>
            </div>
            ';
        }
        ?>
        <div class="box-content d-flex flex-column gap-2">
            <?php
            if ($_GET['payment_method'] !== 'cod') {
                $amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán VNPAY phản hồi
                $payDate = DateTime::createFromFormat('YmdHis', $inputData['vnp_PayDate'], new DateTimeZone('GMT+7'))->format('d/m/Y H:i:s'); // Thời gian thanh toán VNPAY phản hồi
            ?>
                <div class="box-content-item">
                    <span class="box-content-item-title">Mã đơn hàng:</span>
                    <span class="box-content-item-value"><?= Order::getLastestId() ?></span>
                </div>
                <div class="box-content-item">
                    <span class="box-content-item-title">Mã giao dịch:</span>
                    <span class="box-content-item-value"><?= $_GET['vnp_TransactionNo'] == 0 ? 'NULL' : $_GET['vnp_TransactionNo'] ?></span>
                </div>
                <div class="box-content-item">
                    <span class="box-content-item-title">Số tiền:</span>
                    <span class="box-content-item-value"><?= number_format($amount, 0, ',', '.') ?> đ</span>
                </div>
                <div class="box-content-item">
                    <span class="box-content-item-title">Thời gian thanh toán:</span>
                    <span class="box-content-item-value"><?= $payDate ?></span>
                </div>
                <div class="box-content-item">
                    <span class="box-content-item-title">Kết quả:</span>
                    <?php
                    if ($secureHash == $vnp_SecureHash) {
                        if ($_GET['vnp_ResponseCode'] == '00') {
                            echo '<span class="box-content-item-value checkout-success">Giao dịch thành công</span>';
                        } else if ($_GET['vnp_ResponseCode'] == '09') {
                            echo '<span class="box-content-item-value checkout-fail">Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.</span>';
                        } else if ($_GET['vnp_ResponseCode'] == '11') {
                            echo '<span class="box-content-item-value checkout-fail">Giao dịch không thành công do: Đã hết hạn chờ thanh toán.</span>';
                        } else if ($_GET['vnp_ResponseCode'] == '24') {
                            echo '<span class="box-content-item-value checkout-fail">Giao dịch thất bại do khách hàng hủy thanh toán</span>';
                        } else if ($_GET['vnp_ResponseCode'] == '51') {
                            echo '<span class="box-content-item-value checkout-fail">Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.</span>';
                        } else {
                            echo '<span class="box-content-item-value checkout-fail">Đã xảy ra lỗi không xác định</span>';
                        }
                    } else {
                        echo '<span class="box-content-item-value checkout-fail">Chữ ký không hợp lệ.</span>';
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center">
            <a href="?page=home" class="mt-4 text-sucess text-center">Trở về trang chủ</a>
        </div>
    </div>
</main>