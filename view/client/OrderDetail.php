<!-- Lịch sử đơn hàng -->
    <main class="personal-page">
        <div class="container">
            <div class="row personal-page-content">
                <?php
                    include __DIR__.'/../../inc/client/personNavbar.php';
                    $order = $result['order'];
                    $trangthaiDH = ($result['trangthaiDH'])->getTenTT();
                    $customer = $result['customer'];
                    $orderDetails = $result['orderDetails'];
                    $products = $result['products'];
                ?>
                <div class="col-9">
                    <div class="order-list-content">
                        <div class="row order-box b-shadow">
                        <?php
                            if($trangthaiDH == 'Đã giao'){
                        ?>
                            <div class="order-status order-delivered" id="delivered">
                                <i class="fa-regular fa-truck"></i>
                                <h6>Giao hàng thành công</h6>
                            </div>
                        <?php
                            }else if($trangthaiDH == "Đang giao"){
                        ?>
                            <div class="order-status order-delivered" id="return-accepted">
                                <i class="fa-regular fa-truck"></i>
                                <h6>Đang giao</h6>
                            </div>
                        <?php
                            }else if($trangthaiDH == "Chờ duyệt"){
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
                                <h6><?=$trangthaiDH?></h6>
                            </div>
                        <?php
                            }
                        ?>
                            <!-- ------------------- -->
                            <div class="order-info">
                                <div class="order-id">
                                    <strong>Mã đơn hàng: </strong><span id="idDH"><?=$order->getIdDH()?></span>
                                </div>
                                <div class="order-timestamp">
                                    <strong>Ngày tạo đơn hàng: </strong><span class="timestamp"><?=$order->getNgaytao()?></span>
                                </div>
                                <div class="order-timestamp">
                                    <strong>Ngày cập nhật: </strong><span class="timestamp"><?=$order->getNgaycapnhat()?></span>
                                </div>
                                <div class="order-timestamp">
                                    <strong>Phí ship: </strong><span class="timestamp"><?=number_format($order->getPhiship(),0,"",".")?>đ</span>
                                </div>
                                <div class="order-payment-method">
                                    <strong>Phương thức thanh toán: </strong> 
                                    <?php
                                        if($order->getPhuong_thuc_tt() == 'cod'){
                                    ?>
                                    <span class="payment-method"> Tiền mặt (COD)</span> 
                                    <?php
                                        }else{
                                    ?>
                                    <span class="payment-method"> Chuyển khoản</span> 
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="order-cust-info">
                                <strong class="title"><i class="fa-solid fa-location-dot"></i> Thông tin nhận hàng</strong>
                                <div class="cust-name">
                                    <span><?=$customer->getTenTK()?></span>
                                </div>
                                <div class="cust-phone">
                                    <span><?=$customer->getDienthoai()?></span>
                                </div>
                                <div class="cust-address">
                                    <span><?=$order->getDiachi()?></span>
                                </div>
                            </div>
                            <div class="order-product-list">
                                <?php
                                    $n = count($products);
                                    for($i = 0; $i<$n; $i++){
                                ?>
                                <div class="order-product">
                                    <div class="book-image">
                                        <img src="asset/uploads/<?=$products[$i]['hinhanh']?>" alt="">
                                    </div>
                                    <div class="book-info">
                                        <div class="title">
                                            <a href="?page=productDetail&idSach=<?=$products[$i]['idSach']?>"><?=$products[$i]['tuasach']?></a>
                                        </div>
                                        <div class="quantity-text">
                                            x<span class="quantity"><?=$orderDetails[$i]['soluong']?></span>
                                        </div>
                                        <div class="price-text">
                                            <span><?=number_format($orderDetails[$i]['gialucdat'],0,"",".")?></span>đ
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <div class="total-amount">
                                <strong>Thành tiền:</strong>
                                <span class="price-text"><span><?=number_format($order->getTongtien(),0,"",".")?></span>đ</span>
                            </div>
                            
                            <?php
                            if($trangthaiDH == "Chờ duyệt"){

                            ?>
                            <div class="btn-end">
                                <button type="button" id="cancel_btn" class="btn canceled-order" data-bs-toggle="modal" data-bs-target="#canceledOrder">Hủy đơn hàng</button>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<script src="asset/client/js/orderDetail.js"></script>