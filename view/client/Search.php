    <main class="search-book">
        <!-- Tìm kiếm sản phẩm -->
        <div class="container">
            <div class="searching-book-content">
                <!-- bộ lọc -->
                <div class="filter">
                    <div class="category-filter">
                        <div class="dropdown">
                            <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-thin fa-bars"></i>
                            </button>
                            <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="?page=search&category=bestseller">Sách bán chạy</a>
                                    </li>
                                <?php
                                    $categories = $result['category'];
                                    foreach($categories as $item){
                                ?>
                                    <li>
                                        <a class="dropdown-item" href="?page=search&category=<?=$item->getTenTL()?>&idTL=<?=$item->getIdTL()?>"><?=$item->getTenTL()?></a>
                                    </li>
                                <?php
                                    }
                                ?>
                            </ul>
                          </div>
                    </div>
                    <div class="price-filter">
                        <h6>Khoảng giá:</h6>
                        <form id="searchProduct_form">
                            <input type="hidden" name="page" value="search">
                            <input type="text" name="minPrice" id="minPrice">
                            <span><i class="fa-thin fa-minus"></i></span>
                            <input type="text" name="maxPrice" id="maxPrice">
                            <button>Tìm kiếm</button>
                        </form>
                    </div>
                </div>
                <div class="searching-book-box b-shadow">
                    <!-- kết quả tìm kiếm -->
                    <div class="searching-result-text">
                        KẾT QUẢ TÌM KIẾM: 
                    </div>
                    <div class="searching-result-books">
                        <div class="book-list">
                            <ul>
                        <?php 
                            $product = $result['paging'];
                            if (empty($product)) {
                                echo '<div>Không tìm thấy kết quả tìm kiếm</div>';
                            }
                            else{
                                echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                                for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                                    $book = $product[$i];
                        ?>
                                <li class="book-card">
                                    <a href="?page=productDetail&idSach=<?=$book->getIdSach()?>" class="nav-link">
                                        <div class="image-book">
                                            <img src="asset/uploads/<?=$book->getHinhanh()?>" alt="">
                                        </div>
                                        <div class="info-book">
                                            <span class="title"><?=$book->getTuasach()?></span>
                                            <span class="units-sold-text"><span class="units-sold"><?=$book->getLuotBan()?></span> lượt bán</span>
                                            <span class="price-text"> <span class="price"><?=number_format($book->getGiaban(),0,"",".")?></span> đ</span> 
                                            <?php
                                                if($book->getGiaban() != $book->getGiabia()){
                                            ?>
                                                <span class="price-text" style="color: black; text-decoration: line-through;"> <span class="giabia"><?=number_format($book->getGiabia(),0,"",".")?></span> đ</span>
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
                        </div>
                    </div>
                    <!-- phân trang -->
                    <div class="pagination">
                        <div class="pagination-content">
                        <?php           
                            echo $pagingButton;
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
    <script src="asset/client/js/search.js"></script>
