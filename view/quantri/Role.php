    <!-- Content -->
    <main class="container pt-5">
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ NHÓM QUYỀN</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="row d-flex justify-content-between">
            <?php
                if(isset($_SESSION['function']['NQ_them'])){
            ?>
            <div class="col-auto">
                <button class="btn btn-control open_add_form"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#permissionModal"
                >
                    <i class="fa-regular fa-plus me-2"></i>
                    Thêm nhóm quyền
                </button>
            </div>
            <?php
                }
            ?>
            <div class="col">
                <form id="form-search">
                    <div class="input-group">
                        <input type="hidden" name="page" value="searchRole">
                        <input type="text" name="kyw" id="search-role" class="form-control" placeholder="Nhập id, tên nhóm quyền" aria-label="Tìm kiếm nhóm quyền" aria-describedby="search-bar">
                        <button class="btn btn-control" type="submit" id="search-btn">Tìm kiếm</button>
                    </div>
                </form>
            </div>
            <!-- <div class="col-auto">
                <button type="button" class="btn btn-control">Tìm kiếm</button>
            </div> -->
        </div>
        <!-- ... -->
        <!-- Table data -->
        <div class="row mt-5">
            <div class="col">
                <table class="table table-bordered text-center table-hover align-middle border-success">
                    <thead class="table-header">
                        <tr>
                            <th scope="col">Mã nhóm quyền</th>
                            <th>Tên nhóm quyền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $roles = $result['paging'];
                        if($roles == null){
                            echo '<tr><td colspan="4">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        }else{
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $role = $roles[$i];
                        ?>
                        <tr>
                            <td class="role_id"><?=$role->getIdNQ()?></td>
                            <td><?=$role->getTenNQ()?></td>
                            <td class="status">
                                <?php
                                    if($role->getTrangthai())
                                        echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                    else
                                        echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                                ?>
                            </td>
                            <?php
                                if(isset($_SESSION['function']['NQ_xem']) || isset($_SESSION['function']['NQ_sua']) || isset($_SESSION['function']['NQ_xoa'])){
                            ?>
                            <td>
                                <?php
                                    if(isset($_SESSION['function']['NQ_xem'])){
                                ?>
                                <button class="btn fs-5 open_view_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionModal"
                                >
                                    <i class="fa-regular fa-circle-info"></i>
                                </button>
                                <?php
                                    }
                                    if(isset($_SESSION['function']['NQ_sua'])){
                                ?>
                                <button class="btn fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionModal"
                                >
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <?php
                                    }
                                    if(isset($_SESSION['function']['NQ_xoa'])){
                                ?>
                                <span>
                                <?php
                                    if($role->getTrangthai())
                                        echo '
                                            <button class="btn fs-5 lock_role">
                                            <i class="fa-regular fa-lock"></i>
                                            </button>';
                                    else
                                        echo '
                                            <button class="btn fs-5 unlock_role">
                                            <i class="fa-regular fa-unlock"></i>
                                            </button>'
                                ?>
                                </span>
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
    

    <!-- MODAL-->
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="permissionModalLabel">Thêm nhóm quyền</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="permissionForm" style="overflow-y: auto;">
                    <input type="hidden" name="idNQ" id="idNQ" value="">
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="tenNQ" id="permissionGroupName" placeholder="Nhập tên nhóm quyền">
                            <label for="permissionGroupName" style="color: #1D712C;">Tên nhóm quyền</label>
                            <span class="text-message role-name-msg"></span>
                        </div>
   
                        <table class="table table-borderless permission-group">
                            <thead>
                                <tr>
                                    <th class="text-success text-start fs-5">Danh mục chức năng</th>
                                    <th>Xem</th>
                                    <th>Tạo mới</th>
                                    <th>Cập nhật</th>
                                    <th>Khóa</th>
                                    <th>In</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Quản lý nhóm quyền</td>
                                    <td><input type="checkbox" name="NQ_xem" class="form-check-input"></td>
                                    <td><input type="checkbox" name="NQ_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="NQ_sua" class="form-check-input"></td>
                                    <td><input type="checkbox" name="NQ_xoa" class="form-check-input"></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý tài khoản</td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="TK_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="TK_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý tác giả</td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="TG_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="TG_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý danh mục</td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="DM_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="DM_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý nhà cung cấp</td>
                                    <td><input type="checkbox" name="NCC_xem" class="form-check-input"></td>
                                    <td><input type="checkbox" name="NCC_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="NCC_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý mã giảm giá</td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="MGG_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="MGG_sua" class="form-check-input"></td>
                                    <td><input type="checkbox" name="MGG_xoa" class="form-check-input"></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý sản phẩm</td>
                                    <td><input type="checkbox" name="SP_xem" class="form-check-input"></td>
                                    <td><input type="checkbox" name="SP_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="SP_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Quản lý đơn hàng</td>
                                    <td><input type="checkbox" name="DH_xem" class="form-check-input"></td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="DH_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="DH_in" class="form-check-input"></td>
                                </tr>
                                <tr>
                                    <td>Quản lý phiếu nhập sách</td>
                                    <td><input type="checkbox" name="PN_xem" class="form-check-input"></td>
                                    <td><input type="checkbox" name="PN_them" class="form-check-input"></td>
                                    <td><input type="checkbox" name="PN_sua" class="form-check-input"></td>
                                    <td>-</td>
                                    <td><input type="checkbox" name="PN_in" class="form-check-input"></td>
                                </tr>
                                <tr>
                                    <td>Thống kê</td>
                                    <td><input type="checkbox" name="ST_xem" class="form-check-input"></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" class="btn btn-success" id="saveModalBtn">Thêm nhóm quyền</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ... -->
    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Role.js"></script>
</body>
</html>

