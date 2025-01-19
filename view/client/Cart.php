<main>
    <!-- Giỏ hàng -->
    <div class="container cart">
        <div class="cart-content">
            <div class="cart-content-box">
                <div class="title">
                    <h4>Giỏ Hàng Của Bạn</h4>
                </div>
                <div class="row">
                    <div class="col-9">
                        <div class="product-cart-box b-shadow">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" colspan="2">Sản phẩm</th>
                                        <th scope="col" class="qty">Số lượng</th>
                                        <th scope="col">Thành tiền</th>
                                        <th scope="col" colspan="2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_SESSION['cart']) && count($_SESSION['cart']['products']) > 0) {
                                        foreach ($_SESSION['cart']['products'] as $key => $item):
                                            extract($item);
                                    ?>
                                            <tr>
                                                <td class="book-image" scope="row">
                                                    <img src="asset/uploads/<?= $hinhanh ?>" alt="">
                                                </td>
                                                <td class="book-name">
                                                    <?= $tuasach ?>
                                                </td>
                                                <td class="quantity">
                                                    <div id="product_A_form" class="input-quantity">
                                                        <div class="input-quantity-group">
                                                            <button class="item-quantity-btn btn-subtract" type="button">
                                                                <i class="fa-regular fa-minus"></i>
                                                            </button>
                                                            <input data-index=<?= $key ?> data-inStock=<?= $tonkho ?> type="number" min=1 value=<?= $soluong ?> class="text-center item-quantity">
                                                            <button class="item-quantity-btn btn-add" type="button">
                                                                <i class="fa-regular fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="book-price">
                                                    <span class="price-text">
                                                        <span class="price"><?= number_format($giaban, 0, ',', '.') ?></span> đ
                                                    </span>
                                                </td>
                                                <td class="delete-book">
                                                    <button data-index=<?= $key ?> class="icon delete-cart-item">
                                                        <i class="fa-thin fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        endforeach;
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Giỏ hàng trống</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                            </table>
                            <div class="continue-shopping">
                                <a href="?page=home" class="nav-link">
                                    <i class="fa-light fa-arrow-turn-down-left"></i>
                                    <span>Tiếp tục mua hàng</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="order-info-box b-shadow">
                            <div class=title>
                                <h5>Thông Tin Đơn Hàng</h5>
                            </div>
                            <div class="info-order">
                                <div class="order-item quantity">
                                    <strong>Số sản phẩm</strong>
                                    <span class="total-text" id="cart-total-quantity">
                                        <?php
                                        if (!isset($_SESSION['cart']) || count($_SESSION['cart']['products']) == 0) {
                                            echo 0;
                                        } else {
                                            echo array_reduce($_SESSION['cart']['products'], function ($carry, $item) {
                                                return $carry + $item['soluong'];
                                            }, 0);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="order-item total">
                                    <strong>Tổng số tiền</strong>
                                    <span class="total-text">
                                        <span class="total-money" id="cart-total-price">
                                            <?php
                                            if (!isset($_SESSION['cart']) || count($_SESSION['cart']['products']) == 0) {
                                                echo 0;
                                            } else {
                                                $total =  array_reduce($_SESSION['cart']['products'], function ($carry, $item) {
                                                    return $carry + $item['giaban'] * $item['soluong'];
                                                }, 0);
                                                echo number_format($total, 0, ',', '.');
                                            }
                                            ?>
                                        </span>
                                        đ
                                    </span>
                                </div>
                                <div class="submit-purchase-btn">
                                    <a href="?page=checkout-address">
                                        <button class="btn">
                                            Thanh toán
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="asset/client/js/Cart.js"></script>