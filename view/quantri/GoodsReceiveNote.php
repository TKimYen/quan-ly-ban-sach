<main class="container pt-5">
    <!-- Page title -->
    <div class="row">
        <h1 class="page-title">QUẢN LÝ PHIẾU NHẬP SÁCH</h1>
    </div>
    <!-- ... -->
    <!-- Page control -->
    <div class="row d-flex justify-content-between">
        <div class="col">
            <div class="row flex-nowrap">
                <div class="col-auto">
                    <button class="btn btn-control open_add_form"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#grnCreateModal">
                        <i class="fa-regular fa-plus me-2"></i>
                        Thêm phiếu nhập
                    </button>
                </div>
                <div class="col-10">
                    <form class="row" action="" id="searchGRN_form">
                        <input type="hidden" name="page" value="searchGRN">
                        <div class="col-auto">
                            <input type="text"
                                class="form-control"
                                placeholder="Nhập id, tên nhà cung cấp"
                                aria-label="Tìm kiếm phiếu nhập"
                                aria-describedby="search-bar"
                                name="kyw">
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <select id="status-select" class="form-select" name="status_select">
                                    <option selected value="-1">Tất cả trạng thái</option>
                                    <option value="cht">Chưa hoàn thành</option>
                                    <option value="ht">Hoàn thành</option>
                                    <option value="huy">Bị hủy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center flex-nowrap gap-1">
                            <label for="date-start">Từ ngày</label>
                            <input type="date" style="width: 150px;" name="date_start" id="date-start" class="form-control">
                            <label for="date-end">đến ngày</label>
                            <input type="date" style="width: 150px;" name="date_end" id="date-end" class="form-control">
                        </div>
                        <button class="col btn btn-control" type="submite" id="search-btn">Tìm kiếm</button>  
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ... -->
    <!-- Table data -->
    <div class="row mt-5">
        <div class="col">
            <table class="table table-bordered text-center table-hover align-middle border-success">
                <thead class="table-header">
                    <tr>
                        <th scope="col">Mã phiếu nhập</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $grns = $result['paging'];
                        if($grns == null){
                            echo '<tr><td colspan="6">Không có dữ liệu!</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else{
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $grn = $grns[$i];
                    ?>
                    <tr>
                        <td class="grn_id"><?=$grn->getIdPN()?></td>
                        <td><?=$grn->getNgaytao()?></td>
                        <td><?=$grn->getNgaycapnhat()?></td>
                        <td><?=number_format($grn->getTongtien(),0,"",".");?>đ</td>
                        <td>
                            <?php
                                $trangthai = $grn->getTrangthai();
                                if($trangthai == 'cht')
                                    echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Chưa hoàn thành</span>';
                                else if($trangthai == 'ht')
                                    echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoàn thành</span>';
                                else echo '<span class="bagde rounded-2 bg-danger text-white p-2">Bị hủy</span>';
                            ?>
                        </td>
                        <td>
                            <button class="btn fs-5 open_view_form"
                                data-bs-toggle="modal"
                                data-bs-target="#grnModal"
                                title="Xem chi tiết">
                                <i class="fa-regular fa-circle-info"></i>
                            </button>
                            <?php
                                if($trangthai == 'cht'){
                            ?>
                            <button class="btn fs-5 open_edit_form"
                                data-bs-toggle="modal"
                                data-bs-target="#grnModal"
                                title="Chỉnh sửa">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <?php
                                }
                            ?>
                            <a href="../controller/quantri/printGRN.php?idPN=<?=$grn->getIdPN()?>" target="_blank" class="btn fs-5 print_btn"
                                title="In">
                                <i class="fa-regular fa-print"></i>
                            </a>
                        </td>
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

<!-- Modal: Tạo phiếu nhập -->
<div class="modal fade"
    id="grnCreateModal"
    tabindex="-1"
    aria-labelledby="grnCreateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success" id="grnCreateModalLabel">Tạo phiếu nhập</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="grnCreateForm">
                <div class="modal-body">
                    <div class="row mb-3 align-items-center">
                        <label for="grn-supplier-name" class="col-form-label col-sm-4 fw-bold">Tên nhà cung cấp</label>
                        <div class="col">
                            <select name="supplier_id" id="supplier-id" class="form-select">
                                <option value="-1">Chọn nhà cung cấp</option>
                                <?php
                                    $supplier = $result['supplier'];
                                    foreach($supplier as $item){
                                ?>
                                <option value="<?=$item->getIdNCC()?>"><?=$item->getTenNCC()?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <span class="text-message grn-supplier-name-msg"></span>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="grn-discount" class="col-form-label col-sm-4 fw-bold">Chiết khấu (%)</label>
                        <div class="col">
                            <input type="number"
                                name="grn-discount"
                                class="form-control"
                                id="grn-discount"
                                min="0"
                                max="100"
                                placeholder="Nhập phần trăm chiết khấu">
                            <span class="text-message grn-discount-msg"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <input type="hidden" name="" id="submit_btn"> -->
                    <button type="submit"
                        class="btn btn-success"
                        id="saveCreateModalBtn">
                        Tạo phiếu nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ... -->

<!-- Modal: Thêm phiếu nhập -->
<div class="modal fade"
    tabindex="-1"
    id="grnModal"
    aria-labelledby="grnModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title text-success text-uppercase" id="grnModalLabel">Phiếu nhập sách</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="grnForm">
                    <input type="hidden" name="idPN">
                    <div class="modal-grn-info d-flex flex-column gap-1">
                        <div class="grn-info-group d-flex">
                            <div class="grn-info-group-item d-flex flex-column gap-1 w-50">
                                <div class="grn-info d-flex gap-2 w-100 align-items-center">
                                    <span class="grn-info-title">
                                        Trạng thái:
                                    </span>
                                    <span class="grn-info-content not-edit trangthai">
                                        Chưa hoàn thành
                                    </span>
                                    <div class="edit not-view">
                                        <select name="status" id="trangthai" class="form-select grn-status-select">
                                        </select>
                                    </div>
                                </div>
                                <div class="grn-info d-flex gap-2">
                                    <span class="grn-info-title">
                                        Nhà cung cấp:
                                    </span>
                                    <input type="hidden" name="idNCC" id="idNCC">
                                    <span class="grn-info-content" id="tenNCC">
                                    </span>
                                </div>
                                <div class="grn-info d-flex gap-2">
                                    <span class="grn-info-title">
                                        Nhân viên:
                                    </span>
                                    
                                    <input type="hidden" name="idNV" value="">
                                    <span class="grn-info-content staff">
                                        
                                    </span>
                                </div>
                            </div>
                            <div class="grn-info-group-item d-flex flex-column gap-1 w-50">
                                <div class="grn-info d-flex gap-2">
                                    <span class="grn-info-title">
                                        Ngày tạo phiếu:
                                    </span>
                                    <input type="hidden" name="ngaytao" value="">
                                    <span class="grn-info-content" id="ngaytao">
                                        
                                    </span>
                                </div>
                                <div class="grn-info d-flex gap-2" >
                                    <span class="grn-info-title">
                                        Ngày cập nhật
                                    </span>
                                    <input type="hidden" name="ngaycapnhat" value="">
                                    <span class="grn-info-content" id="ngaycapnhat">
                                       
                                    </span>
                                </div>
                                <div class="grn-info d-flex gap-2">
                                    <span class="grn-info-title">
                                        Chiết khấu:
                                    </span>
                                    <input type="hidden" name="chietkhau">
                                    <span class="grn-info-content" id="chietkhau">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="grn-info d-flex gap-2 text-danger fw-bold">
                            <span class="grn-info-title">
                                Tổng tiền:
                            </span>
                            <input type="hidden" name="tongtien">
                            <span class="grn-info-content" id="tongtien">
                                
                            </span>
                        </div>
                    </div>
                    <div class="modal-grn-controls my-3">
                        <!-- Bỏ row-count thì sửa lại vị trí của nút add-row-btn sang sát lề phải -->
                        <p class="row-count">
                            Đang có <span class="fw-bold">0</span> sản phẩm trong phiếu nhập.
                        </p>
                        <button type="button" id="add-row-btn" class="btn btn-success grn-controls add-row not-view not-edit">
                            <i class="fa-regular fa-file-plus"></i>
                            Thêm dòng
                        </button>
                    </div>
                    <div class="modal-table-conent">
                        <table class="table table-bordered text-center table-hover align-middle border-success">
                            <thead class="table-header">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sách</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Đơn giá bìa</th>
                                    <th>Thành tiền</th>
                                    <th class="not-view not-view-edit">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="details">
                                <tr class="grn-row-template">
                                    <td></td>
                                    <td>
                                        <select name="grn_product[]" class="form-select not-view grn_product">
                                            
                                        </select>
                                        <span class="view"></span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control grn-quantity not-view" name="grn_quantity[]" min="1">
                                        <span class="view"></span>
                                    </td>
                                    <input type="hidden" name="gianhap[]">
                                    <td class="gianhap">
                                        
                                        <!-- Dùng JS tính giá nhập (dựa vào giá bìa & chiết khấu) -->
                                        
                                    </td>
                                    <td class="giabia">
                                        <!-- Dùng AJAX hiển thị giá bìa -->
                                       
                                    </td>
                                    <input type="hidden" name="thanhtien[]">
                                    <td class="thanhtien">
                                        
                                        <!-- Dùng JS tính thành tiền -->
                                       
                                    </td>
                                    <td class="not-view">
                                        <button type="button" class="btn delete-row" title="Xóa hàng">
                                            <i class="fa-regular fa-delete-left"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer not-view">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" class="btn btn-success" id="saveModalBtn">Thêm phiếu nhập</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
<!-- ... -->

<!-- Modal: Thêm sản phẩm-->
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
                    <input type="hidden" name="" id="submit_btn_product">
                    <button type="submit" class="btn btn-success" id="saveModalBtnProduct">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ... -->

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

<!-- Link JS -->
<script src="../asset/quantri/js/GoodsReceiveNote.js?v=<?php echo time(); ?>"></script>

<!-- <script src="../asset/quantri/js/GoodsReceiveNote.js"></script> -->
</body>
</html>