<!-- Content -->
<main class="container pt-5">
    <!-- Page title -->
    <div class="row">
        <h1 class="page-title">QUẢN LÝ SẢN PHẨM</h1>
    </div>
    <!-- ... -->
    <!-- Page tabs -->
    <?php
        if(isset($_SESSION['function']['SP_them'])){
    ?>
    <div class="row mb-3">
        <div class="col d-flex justify-content-end align-items-center">
            <button class="btn btn-control open_add_form"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#productModal"
            >
                <i class="fa-regular fa-plus me-2"></i>
                Thêm sản phẩm
            </button>
        </div>
    </div>
    <?php
        }
    ?>
    <!-- ... -->
     <!-- Page control -->
     <div class="row d-flex justify-content-between">
        <div class="col">
            <form class="row flex-nowrap" id="searchProduct_form">
                <input type="hidden" name="page" value="searchProduct">
                <div class="col-auto">
                    <div class="input-group">
                        <select id="category-select" class="form-select" name="category_select">
                            <option selected value="-1">Tất cả danh mục</option>
                            <?php
                                $category = Category::getAll();
                                foreach($category as $item) {
                            ?>
                            <option value="<?=$item->getIdTL()?>"><?=$item->getTenTL()?></option>
                           
                            <?php  
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text"
                            class="form-control"
                            placeholder="Nhập id, tên sản phẩm"
                            aria-label="Tìm kiếm sản phẩm"
                            aria-describedby="search-bar"
                            name="kyw">
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center flex-nowrap">
                    <span class="me-2">Khoảng giá</span>
                    <input class="form-control me-2" style="width: 120px;" type="number" name="price_min" id="price-min" min="0">
                    <span class="mx-2">-</span>
                    <input class="form-control" style="width: 120px;" type="number" name="price_max" id="price-max" min="0">
                </div>
                <button class="col-auto btn btn-control" type="submit" id="search-btn">Tìm kiếm</button>
                <div class="col-auto d-flex align-items-center gap-2">
                    <span>Tồn kho</span>
                    <button class="btn btn-control" name="sort" value="sort19">
                        <i class="fa-regular fa-arrow-down-1-9"></i>
                    </button>
                    <button class="btn btn-control" name="sort" value="sort91">
                        <i class="fa-regular fa-arrow-down-9-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- ... -->
    <!-- Table data -->
    <div class="row mt-5">
        <div class="col">
            <table class="table table-bordered text-center table-hover align-middle border-success">
                <thead class="table-header">
                    <tr>
                        <th scope="col">Mã sách</th>
                        <th>Hình ảnh</th>
                        <th>Tên sách</th>
                        <th>Tồn kho</th>
                        <th>Giá bán</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $products = $result['paging'];
                        if($products == NULL){
                            echo '<tr><td colspan="7">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        }else{
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $product = $products[$i];
                        ?>
                        <tr>
                            <td class="product_id"><?=$product->getIdSach()?></td>
                            <td>
                                <img src="../asset/uploads/<?=$product->getHinhanh()?>" class="book-img">
                            </td>
                            <td><?=$product->getTuasach()?></td>
                            <td><?=$product->getTonkho()?></td>
                            <td><?=number_format($product->getGiaban(),0,"",".");?>đ</td>
                            <td>
                                <?php
                                        if($product->getTrangthai())
                                            echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                        else
                                            echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                                ?>
                            </td>
                            <?php
                                if(isset($_SESSION['function']['SP_xem']) || isset($_SESSION['function']['SP_sua'])){
                            ?>
                            <td>
                                <?php
                                    if(isset($_SESSION['function']['SP_xem'])){
                                ?>
                                <button class="btn fs-5 open_view_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productViewModal"
                                >
                                    <i class="fa-regular fa-circle-info"></i>
                                </button>
                                <?php
                                    }
                                    if(isset($_SESSION['function']['SP_sua'])){
                                ?>
                                <button class="btn fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
                                >
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <?php
                                    }
                                ?>
                            </td>
                            <?php
                                }
                            ?>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- ... -->
    <!-- Pagination -->
    <div class="row mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                <?php
                    echo $pagingButton;
                ?>
                </ul>
              </nav>
        </div>
    <?php
        }
    ?>
        <!-- ... -->
</main>
<!-- ... -->

<!-- Modal -->
<div class="modal fade"
    id="productModal"
    tabindex="-1"
    aria-labelledby="productModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success" id="productModalLabel">Thêm sản phẩm</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="productForm" style="overflow-y: auto;" enctype="multipart/form-data">
                <input type="hidden" name="product-id" id="product-id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4 modal-body-left px-3">
                            <div class="img-preview-container">
                                <div class="upload-img-preview">
                                    <img src="../asset/quantri/img/blank-image.png" alt="" id="img-preview" class="img-preview">
                                </div>
                            </div>
                            <div class="form-group-file">
                                <label for="upload-img" class="form-label-file">
                                    <i class="fa-regular fa-up-from-dotted-line"></i>
                                    Tải hình ảnh
                                </label>
                                <div class="text-message product-img-msg"></div>
                                <input type="file"
                                    name="product-img"
                                    id="upload-img"
                                    class="form-control"
                                    accept="image/jpeg, image/png, image/jpg, image/webp">
                                <!--<input type="hidden" name="product_images">-->
                            </div>
                        </div>
                        <div class="col-8 modal-body-right px-4">
                            <div class="row mb-3">
                                <label for="product-name" class="col-form-label col-sm-3 fw-bold">Tên sách</label>
                                <div class="col">
                                    <input type="text"
                                        name="product-name"
                                        class="form-control"
                                        id="product-name"
                                        placeholder="Nhập tên sách">
                                    <span class="text-message product-name-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-publisher" class="col-form-label col-sm-3 fw-bold">Nhà xuất bản</label>
                                <div class="col">
                                    <input type="text"
                                        name="product-publisher"
                                        class="form-control"
                                        id="product-publisher"
                                        placeholder="Nhập tên nhà xuất bản">
                                    <span class="text-message product-publisher-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-supplier" class="col-form-label col-sm-3 fw-bold">Nhà cung cấp</label>
                                <div class="col">
                                    <div id="tenncc"></div>
                                    <input type="hidden" name="idNCC">
                                    <select name="product-supplier" id="product-supplier" class="form-select">
                                        <option selected="selected" value="">Chọn nhà cung cấp</option>
                                        <?php
                                            $supplier = Supplier::getAllActive();
                                            foreach($supplier as $item){
                                        ?>
                                            <option value="<?=$item->getIdNCC()?>"><?=$item->getTenNCC()?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <span class="text-message product-supplier-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-author" class="col-form-label col-sm-3 fw-bold">Tác giả</label>
                                <div class="col">
                                    <button class="btn btn-success"
                                        id="openSelectAuthorModalBtn"
                                        type="button">
                                        Chọn tác giả
                                    </button>

                                    <span class="text-message product-author-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-category" class="col-form-label col-sm-3 fw-bold">Thể loại</label>
                                <div class="col">
                                    <select name="product-category" id="product-category" class="form-select">
                                        <option value="" selected="selected">Chọn thể loại</option>
                                        <?php
                                            $category = Category::getAll();
                                            foreach($category as $item){
                                        ?>  
                                            <option value="<?=$item->getIdTL()?>"><?=$item->getTenTL()?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <span class="text-message product-category-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-original-price" class="col-form-label col-sm-3 fw-bold">Giá bìa</label>
                                <div class="col">
                                    <div class="input-group">
                                        <input type="number"
                                               min="0"
                                            name="product-original-price"
                                            class="form-control"
                                            id="product-original-price"
                                            placeholder="Nhập giá bìa">
                                        <span class="input-group-text">đồng</span>
                                    </div>
                                    <span class="text-message product-original-price-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-publish-year" class="col-form-label col-sm-3 fw-bold">Năm xuất bản</label>
                                <div class="col">
                                    <input type="number"
                                           min="0"
                                        name="product-publish-year"
                                        class="form-control"
                                        id="product-publish-year"
                                        placeholder="Nhập năm xuất bản">
                                    <span class="text-message product-publish-year-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-weight" class="col-form-label col-sm-3 fw-bold">Trọng lượng</label>
                                <div class="col">
                                    <div class="input-group">
                                        <input type="number"
                                               min="0"
                                            name="product-weight"
                                            class="form-control"
                                            id="product-weight"
                                            placeholder="Nhập trọng lượng">
                                        <span class="input-group-text">g</span>
                                    </div>
                                    <span class="text-message product-weight-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3 edit">
                                <label for="product-discount" class="col-form-label col-sm-3 fw-bold">Giảm giá (%)</label>
                                <div class="col">
                                    <select name="product-discount" id="product-discount" class="form-select">
                                        <option value="" selected="selected">Chọn mã giảm giá</option>
                                        <?php
                                            $discount = Discount::getAllWaiting();
                                            foreach($discount as $item){
                                        ?>
                                            <option value="<?=$item->getIdMGG()?>"><?=$item->getPhantram()?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <span class="text-message product-discount-msg"></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product-description" class="col-form-label col-sm-3 fw-bold">Mô tả</label>
                                <div class="col">
                                    <textarea
                                        rows="5"
                                        name="product-description"
                                        class="form-control"
                                        id="product-description">
                                        </textarea>
                                    <span class="text-message product-description-msg"></span>
                                </div>
                            </div>
                            <div class="row align-items-center edit">
                                <label class="col-form-label col-sm-3 fw-bold">Trạng thái</label>
                                <div class="col form-check form-switch ps-5">
                                    <input type="checkbox"
                                        name="status"
                                        id="status"
                                        class="form-check-input"
                                        role="switch"
                                        checked
                                        onchange="document.getElementById('switch-label').textContent = this.checked ? 'Đang bán' : 'Bị ẩn';">
                                    <label for="status" class="form-check-label" id="switch-label">Đang bán</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="" id="submit_btn">
                    <button type="submit" class="btn btn-success" id="saveModalBtn">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- selectAuthor -->
<div
    class = "modal fade"                                       
    id="selectAuthorModal"
    tabindex="-1"
    aria-labelledby="selectAuthorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success" id="selectAuthorModalLabel">Chọn tác giả</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-5">
                <div class="modal-body">
                    <div class="row row-cols-sm-1 row-cols-md-2 g-3">
                        <?php
                            $author = Author::getAll();
                            foreach($author as $item){
                        ?>
                            <div class="col form-check">
                                <input  type="checkbox" 
                                        name="idTG"
                                        value="<?=$item->getIdTG()?>"
                                        class="form-check-input me-2 select-author-checkbox"
                                >
                                <label class="form-check-label"><?=$item->getTenTG()?></label>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="saveSubModalBtn">Chọn</button>
            </div>
        </div>
    </div>
</div>
<!-- viewDetail -->
<div    class="modal fade"
        id="productViewModal"
        tabindex="-1"
        aria-labelledby="productViewModalLabel"
        aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success" id="productViewModalLabel">Chi tiết sản phẩm</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Render... -->
            </div>
        </div>
    </div>
</div>
<!-- ... -->

<!-- Script -->
<script src="../asset/quantri/js/Product.js"></script>