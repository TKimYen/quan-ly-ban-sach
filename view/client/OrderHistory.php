    <!-- Lịch sử đơn hàng -->
    <main class="personal-page">
        <div class="container">
            <div class="row personal-page-content">
                <?php
                    include __DIR__.'/../../inc/client/personNavbar.php';
                ?>
                <div class="col-9">
                    <div class="order-list-content">
                        <?php
                            if($result == null){
                                echo '<div>Không có đơn hàng</div>';
                            }else{
                            foreach($result as $item){
                        ?>
                        <div class="row order-box b-shadow">
                            <!-- ------------------- -->
                            <!-- này là trạng thái đơn hàng  -->
                        <?php
                            if($item['trangthaiDH'] == 'Đã giao'){
                        ?>
                            <div class="order-status order-delivered" id="delivered">
                                <i class="fa-regular fa-truck"></i>
                                <h6>Giao hàng thành công</h6>
                            </div>
                        <?php
                            }else if($item['trangthaiDH'] == "Đang giao"){
                        ?>
                            <div class="order-status order-delivered" id="return-accepted">
                                <i class="fa-regular fa-truck"></i>
                                <h6>Đang giao</h6>
                            </div>
                        <?php
                            }else if($item['trangthaiDH'] == "Chờ duyệt"){
                        ?>
                            <div class="order-status order-pending" id="pending">
                                <i class="fa-regular fa-truck"></i>
                                <h6>Đang chờ duyệt</h6>
                            </div>
                        <?php
                            }else{
                        ?>
                            <div class="order-status order-canceled" id="canceled">
                                <i class="fa-regular fa-truck"></i>
                                <h6><?=$item['trangthaiDH']?></h6>
                            </div>
                        <?php
                            }
                        ?>
                            <!-- ------------------- -->
                            <div class="order-product-1st">
                                <div class="book-image">
                                    <img src="asset/uploads/<?=$item['hinhanh']?>" alt="">
                                </div>
                                <div class="book-info">
                                    <div class="title">
                                        <p style="font-size: 20px; font-weight: bold"><?=$item['tuasach']?></p>
                                    </div>
                                    <div class="quantity-text">
                                        x<span class="quantity"><?=$item['soluong']?></span>
                                    </div>
                                    <div class="quantity-text" style="margin-top: 15px">
                                        <span>Tổng <?=$item['tongsoluong']?> sản phẩm</span>
                                    </div>
                                    <div class="see-more">
                                        <a href="?page=orderDetail&idDH=<?=$item['idDonHang']?>" class="nav-link">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                            <div class="total-amount">
                                <strong>Thành tiền:</strong>
                                <span class="price-text"><span><?=number_format($item['tamtinh'] + $item['phiship'],0,"",".")?></span>đ</span>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>