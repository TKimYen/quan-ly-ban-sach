<main class="homepage">
        <div class="container">
            <div class="row homepage-content">
                <!-- Cột bên trái gồm: danh mục sách, best seller -->
                <div class="col-3">
                    <!-- Danh mục sách -->
                    <div class="category">
                        <div class="category-box">
                            <ul>
                                <p>Danh mục</p>
                                <?php
                                    $categories = $result['category'];
                                    foreach($categories as $item){
                                ?>
                                    <li>
                                        <a href="?page=search&category=<?=$item->getTenTL()?>&idTL=<?=$item->getIdTL()?>" class="nav-link">
                                            <?=$item->getTenTL()?>
                                        </a>
                                    </li>
                                <?php  
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <!-- Danh sách sách best seller -->
                    <div class="best-seller">
                        <div class="best-seller-box">
                            <ul>
                                <p>Best Seller</p>
                                <?php
                                    $bestSeller = $result['bestSellers'];
                                    
                                    if($bestSeller!=null){
                                    foreach($bestSeller as $item){
                                ?>
                                    <li>
                                        <a href="?page=productDetail&idSach=<?=$item->getIdSach()?>" class="nav-link book-card">
                                            <div class="image-book">
                                                <img src="asset/uploads/<?=$item->getHinhanh()?>" alt="">
                                            </div>
                                            <div class="info-book">
                                                <span class="book-title">
                                                    <?=$item->getTuasach()?>
                                                </span>
                                                <span class="units-sold-text"><span class="units-sold"><?=$item->getLuotBan()?></span> lượt bán</span>
                                                <span class="price-text"> <span class="price"><?=number_format($item->getGiaban(),0,"",".")?></span> đ</span> 
                                                <?php
                                                    if($item->getGiaban() != $item->getGiabia()){
                                                ?>
                                                    <span class="price-text" style="color: black; text-decoration: line-through;"> <span class="giabia"><?=number_format($item->getGiabia(),0,"",".")?></span> đ</span>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </a>
                                    </li>
                                <?php
                                    }
                                ?>
                            </ul>
                            <div class="see-more">
                                <a href="?page=search&category=bestseller" class="nav-link">Xem thêm</a>
                            </div>
                            <?php
                                } else echo '<li>Không có sản phẩm</li>';
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Cột bên phải gồm: banner, hiển thị sách theo danh mục -->
                <div class="col-9">
                    <!-- banner -->
                    <div class="banner b-shadow">
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                    $i = 1;
                                    $bestSeller = $result['bestSellers'];
                                    foreach($bestSeller as $item){
                                        // extract($item);
                                        $mota = $item->getMoTa();
                                        if(strlen($mota) > 450) $mota = substr($mota, 0, 450) . '...';
                                        if($i++ == 1 ){
                                ?>
                                    <div class="carousel-item active">
                                <?php
                                        }
                                        else{
                                ?>
                                    <div class="carousel-item">   
                                <?php
                                        }
                                ?>
                                        <div class="banner-content">
                                            <div class="image-book">
                                                <img src="asset/uploads/<?=$item->getHinhanh()?>" class="d-block" alt="...">
                                            </div>
                                            <div class="info-book">
                                                <div class="title">
                                                    <h4><?=$item->getTuasach()?></h4>
                                                </div>
                                                <div class="description">
                                                    <p><?=$item->getMoTa()?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    
                                    }
                                ?>
                              
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <?php           
                        foreach($categories as $cate){
                            // extract($item);
                    ?>
                    <div class="category-book">
                        <div class="title">
                            <h3>
                                <?=$cate->getTenTL()?>
                            </h3>
                        </div>
                        <div class="category-book-box b-shadow">
                            <div class="category-book-content">
                    <?php
                        $product = Product::getBooksByCategory($cate->getIdTL(), false);
                        if (!empty($product)) { 
                        foreach($product as $book){
                            // extract($book);
                    ?>
                                <div class="book-card">
                                    <a href="?page=productDetail&idSach=<?=$book->getIdSach()?>" class="nav-link">
                                        <div class="image-book">
                                            <img src="asset/uploads/<?=$book->getHinhanh()?>" alt="">
                                        </div>
                                        <div class="info-book">
                                            <div class="title">
                                                <h6><?=$book->getTuasach()?></h6>
                                            </div>
                                            <div class="units-sold-text"><span class="units-sold"><?=$book->getLuotBan()?></span> lượt bán</div>
                                            <div class="price-text"> <span class="price"><?=number_format($book->getGiaban(),0,"",".");?></span> đ</div> 
                                            <?php
                                                if($book->getGiaban() != $book->getGiabia()){
                                            ?>
                                                <div class="price-text" style="color: black; text-decoration: line-through;"> <span class="giabia"><?=number_format($item->getGiabia(),0,"",".")?></span> đ</div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </a>
                                </div>
                    <?php
                        }
                    ?>
                            </div>
                            <div class="see-more">
                                <a href="?page=search&category=<?=$cate->getTenTL()?>&idTL=<?=$cate->getIdTL()?>" class="btn nav-link">Xem thêm</a>
                            </div>
                            <?php
                                } else echo '<div>Không có sản phẩm</div>';
                            ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

