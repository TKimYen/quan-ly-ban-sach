<main>
    <!-- Thanh toán -->
    <div class="container paying">
        <div class="paying-content">
            <div class="title">
                <h4>Thanh Toán</h4>
            </div>
            <div class="delivery-info-box b-shadow">
                <div class="title">
                    <i class="fa-solid fa-location-dot"></i>
                    <h5>Địa chỉ nhận hàng</h5>
                </div>
                <div class="customer-info-delivery">
                    <div class="info">
                        <span class="customer-name"><?= $_SESSION['user']['tenTK'] ?></span>
                    </div>
                    <div class="customer-address w-100">
                        <form id="checkout-address-form">
                            <div>
                                <select class="form-select" id="province-selectbox">
                                    <option value="0" selected>Chọn Tỉnh/Thành phố</option>
                                    <?php 
                                        foreach(City::getAll() as $tinh):
                                    ?>
                                    <option value="<?= $tinh['idTinh'] ?>"><?= trim($tinh["tenTinh"]) ?></option>
                                    <?php 
                                        endforeach;
                                    ?>
                                </select>
                                <select class="form-select" id="district-selectbox">
                                    <option value="0" selected>Chọn Quận/Huyện</option>
                                </select>
                                <select class="form-select" id="ward-selectbox">
                                    <option value="0" selected>Chọn Phường/Xã</option>
                                </select>
                                <input type="text" class="form-control" id="address-input" placeholder="Nhập địa chỉ cụ thể...">
                            </div>
                            <button type="submit" class="mt-3 btn btn-success w-100 text-white">
                                <i class="fa-solid fa-check me-3"></i>
                                <span>Xác nhận</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="asset/client/js/Checkout.js"></script>