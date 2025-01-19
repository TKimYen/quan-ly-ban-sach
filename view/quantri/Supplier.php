<!-- Content -->
<main class="container pt-5">
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ NHÀ CUNG CẤP</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="row d-flex justify-content-between">
            <?php
                if(isset($_SESSION['function']['NCC_them'])){
            ?>
            <div class="col-auto">
                <button class="btn btn-control open_add_form" 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#supplierModal"
                >
                    <i class="fa-regular fa-plus me-2"></i>
                    Thêm nhà cung cấp
                </button>
            </div>
            <?php
                }
            ?>
            <div class="col">
                <div class="row">
                    <form class="col">
                        <input type="hidden" name="page" value="searchSupplier">
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text"
                                            class="form-control"
                                            placeholder="Nhập id, tên nhà cung cấp"
                                            aria-label="Tìm kiếm nhà cung cấp"
                                            aria-describedby="search-bar"
                                            name="kyw"
                                    >
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <select id="status-select" class="form-select" name="status_select">
                                        <option selected value="-1">Tất cả trạng thái</option>
                                        <option value="0">Bị khóa</option>
                                        <option value="1">Đang hoạt động</option>
                                    </select>
                                </div>
                            </div>
                            <button class="col-auto btn btn-control" type="submit" id="search-btn">Tìm kiếm</button>
                            <div class="col-auto align-items-center">
                                <span class="me-2">Tên nhà cung cấp</span>
                                <button class="btn btn-control" name="sort" value="AZ">
                                    <i class="fa-regular fa-arrow-down-a-z"></i>
                                </button>
                                <button class="btn btn-control" name="sort" value="ZA">
                                    <i class="fa-regular fa-arrow-up-z-a"></i>
                                </button>
                            </div>
                        </div>
                       
                    </form>

                </div>
            </div>
            <!-- <div class="col-auto"><button onclick="location.reload()" type="button" class="btn btn-control">Làm mới</button>
            </div> -->
        </div>
        <!-- ... -->
        <!-- Table data -->
        <div class="row mt-5">
            <div class="col">
                <table class="table table-bordered text-center table-hover align-middle border-success">
                    <thead class="table-header">
                        <tr>
                            <th scope="col">Mã nhà cung cấp</th>
                            <th>Tên nhà cung cấp</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $supplier = $result['paging'];
                        if($supplier == null) {
                            echo '<tr><td colspan="6">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $item = $supplier[$i];
                        ?>
                        <tr>
                            <td class="supplier_id"><?=$item->getIdNCC()?></td>
                            <td><?=$item->getTenNCC()?></td>
                            <td><?=$item->getEmail()?></td>
                            <td><?=$item->getDienthoai()?></td>
                            <td>
                            <?php
                            if($item->getTrangthai()) 
                            echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                            else echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>';
                            ?>
                            </td>
                            <?php
                                if(isset($_SESSION['function']['NCC_xem']) || isset($_SESSION['function']['NCC_sua'])){
                            ?>
                            <td>
                                <?php
                                    if(isset($_SESSION['function']['NCC_xem'])){
                                ?>
                                <button class="btn fs-5 open_view_form" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#supplierModal"
                                >
                                    <i class="fa-regular fa-circle-info"></i>
                                </button>
                                <?php
                                    }
                                    if(isset($_SESSION['function']['NCC_sua'])){
                                ?>
                                <button class="btn fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#supplierModal"
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
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="supplierModalLabel">Thêm nhà cung cấp</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="supplierForm">
                    <input type="hidden" name="supplier_id" id="supplier_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="supplier-name" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" name="supplier_name" id="supplier-name" class="form-control" placeholder="Nhập tên nhà cung cấp">
                            <span class="text-message supplier-name-msg"></span>
                        </div>
                        <div class="mb-3">
                            <label for="supplier-email" class="form-label">Email</label>
                            <input type="email" name="supplier_email" id="supplier-email" class="form-control" placeholder="Nhập địa chỉ email">
                            <span class="text-message supplier-email-msg"></span>
                        </div>
                        <div class="mb-3">
                            <label for="supplier-phone" class="form-label">Số điện thoại</label>
                            <input type="tel" name="supplier_phone" id="supplier-phone" class="form-control" placeholder="Nhập số điện thoại">
                            <span class="text-message supplier-phone-msg"></span>
                        </div>
                        <div class="mb-3">
                            <label for="supplier-address" class="form-label">Địa chỉ</label>
                            <input type="text" name="supplier_address" id="supplier-address" class="form-control" placeholder="Nhập số nhà, tên đường">
                            <span class="text-message supplier-address-msg"></span>
                        </div>
                        <div class="row mb-3 not-view">
                            <div class="col-md-4"><label for="supplier-city" class="form-label">Tỉnh/thành</label>
                                <select name="supplier_city" id="supplier-city" class="form-select">
                                    <option value='-1'>Chọn tỉnh/thành</option>
                                    <?php
                                        foreach($provinces as $item){
                                            extract($item);
                                    ?>
                                        <option value="<?=$idTinh?>"><?=$tenTinh?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <span class="text-message supplier-province-msg"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="supplier-district" class="form-label">Quận/huyện</label>
                                <select name="supplier_district" id="supplier-district" class="form-select">
                                    <option value="-1">Chọn quận/huyện</option>
                                </select>
                                <span class="text-message supplier-district-msg"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="supplier-ward" class="form-label">Phường/xã</label>
                                <select name="supplier_ward" id="supplier-ward" class="form-select">
                                    <option value="-1">Chọn phường/xã</option>
                                </select>
                                <span class="text-message supplier-ward-msg"></span>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center edit">
                            <label class="col-form-label col-sm-3">Trạng thái</label>
                            <div class="col form-check form-switch ps-5">
                                <input  type="checkbox" 
                                        name="status" 
                                        id="status" 
                                        class="form-check-input" 
                                        role="switch" 
                                        checked
                                        onchange="document.getElementById('switch-label').textContent = this.checked ? 'Đang hoạt động' : 'Bị khóa';"
                                >
                                <label for="status" class="form-check-label" id="switch-label">Đang hoạt động</label>
                            </div>
                        </div>
                        <span class="text-message supplier-msg"></span>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" class="btn btn-success" id="saveModalBtn">Thêm nhà cung cấp</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ... -->

    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Supplier.js"></script>
</body>
</html>