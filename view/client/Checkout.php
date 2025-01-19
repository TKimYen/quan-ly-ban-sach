<main>
        <!-- Thanh toán -->
        <div class="container paying">
            <div class="paying-content">
                <div class="title">
                    <h4>Thanh Toán</h4>
                </div>
                <div class="product-paying row">
                    <div class="col-9">
                        <div class="product-list-box b-shadow">
                            <div class="title">
                                <h5>Sản phẩm</h5>
                            </div>
                            <table class="table">
                                <tbody>
                                    <?php
                                    foreach ($_SESSION['cart']['products'] as $key => $item):
                                        extract($item);
                                    ?>
                                        <tr>
                                            <td class="book-image">
                                                <img src="asset/uploads/<?= $hinhanh ?>" alt="">
                                            </td>
                                            <td class="book-title">
                                                <?= $tuasach ?>
                                            </td>
                                            <td class="book-quantity">
                                                <div class="book-quantity-text">
                                                    x<span class="quantity"><?= $soluong ?></span>
                                                </div>
                                            </td>
                                            <td class="book-price">
                                                <div class="book-price-text">
                                                    <span class="price"><?= number_format($giaban, 0, ',', '.') ?></span>đ
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="payment-method-box b-shadow">
                            <h5 class="text-center">Tóm tắt đơn hàng</h5>
                            <div class="checkout-summary">
                                <div class="checkout-summary-info">
                                    <strong>Số sản phẩm</strong>
                                    <span class="total-text">
                                        <?= array_reduce($_SESSION['cart']['products'], function ($carry, $item) {
                                            return $carry + $item['soluong'];
                                        }, 0); ?>
                                    </span>
                                </div>
                                <div class="checkout-summary-info">
                                    <strong>Tạm tính</strong>
                                    <span class="total-text">
                                        <?php
                                        $total =  array_reduce($_SESSION['cart']['products'], function ($carry, $item) {
                                            return $carry + $item['giaban'] * $item['soluong'];
                                        }, 0);
                                        echo number_format($total, 0, ',', '.');
                                        ?>
                                        đ
                                    </span>
                                </div>
                                <div class="checkout-summary-info">
                                    <strong>Phí vận chuyển</strong>
                                    <span class="total-text">
                                        <?php
                                        if ($_SESSION["cart"]["phi_van_chuyen"] == 0) {
                                            echo "Miễn phí";
                                        } else {
                                            echo number_format($_SESSION["cart"]["phi_van_chuyen"], 0, ',', '.') . "đ";
                                        }
                                        ?>
                                    </span>
                                </div>
                                <hr>
                                <div class="checkout-summary-info fw-bold text-success">
                                    <span>TỔNG CỘNG</span>
                                    <span class="total-text">
                                        <?php
                                        $sum = $total += $_SESSION["cart"]["phi_van_chuyen"];
                                        echo number_format($total, 0, ',', '.');
                                        ?>
                                        đ
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method-box b-shadow">
                            <h5 class="text-center">Phương thức thanh toán</h5>
                            <form action="controller/client/VNPAYCreatePayment.php"
                                id="payment-method-form"
                                method="POST">
                                <div class="form-check form-check-banking">
                                    <input class="form-check-input" type="radio" name="payment-method" id="banking" value="ck">
                                    <label class="form-check-label" for="banking">
                                        Chuyển khoản qua VNPAY
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment-method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        Thanh toán bằng tiền mặt (COD)
                                    </label>
                                </div>
                                <div class="paying-btn">
                                    <button type="submit" class="btn" id="checkout-submit-btn">Thanh toán</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="asset/client/js/Checkout.js"></script>