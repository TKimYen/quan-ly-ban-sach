<main class="container pt-5">
        <!-- 2. -->
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ DANH MỤC</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="row d-flex justify-content-between">
            <?php
                if(isset($_SESSION['function']['DM_them'])){
            ?>
            <div class="col-auto">
                <button class="btn btn-control open_add_form" 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#categoryModal"
                >
                    <i class="fa-regular fa-plus me-2"></i>
                    Thêm danh mục
                </button>
            </div> 
            <?php
                }
            ?>
            <div class="col">
                <form id="search">
                    <input type="hidden" name="page" value="searchCategory">
                    <!-- <input type="hidden" name="page" value="searchCategory"> -->
                    <div class="input-group">
                        <input type="text"
                                class="form-control"
                                placeholder="Nhập id, tên danh mục"
                                aria-label="Tìm kiếm danh mục"
                                aria-describedby="search-bar"
                                name="kyw"
                                id="search-input"
                        >
                        <button class="btn btn-control" type="submit" id="search-btn">Tìm kiếm</button>
                    </div>
                </form>
            </div>

            <!-- <div class="col-auto">
                <button onclick="location.reload()" type="button" class="btn btn-control">Làm mới</button>
            </div> -->
        </div>
        <!-- ... -->
        <!-- Table data -->
        <div class="row mt-5">
            <div class="col">
                <table class="table table-bordered text-center table-hover align-middle border-success">
                    <thead class="table-header">
                        <tr>
                            <th scope="col">Mã danh mục</th>
                            <th>Tên danh mục</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $categories = $result['paging'];
                         if($categories == null) {
                            echo '<tr><td colspan="4">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $category = $categories[$i];
                        ?>
                        <tr>
                            <td class="category_id"><?=$category->getIdTL()?></td>
                            <td class ="category_name"><?=$category->getTenTL()?></td>
                            <td>
                                <?php
                                    if($category->getTrangthai())
                                        echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                    else
                                        echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                                ?>
                            </td>
                            <?php
                                if(isset($_SESSION['function']['DM_sua'])){
                            ?>
                            <td>
                                <button class="btn open-edit-modal fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#categoryModal"
                                >
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
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
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="categoryModalLabel">Thêm danh mục</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm">
                    <input type="hidden" name="category_id" id="category_id" value=" " >
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="category-name" class="col-form-label col-sm-4">Tên danh mục</label>
                            <div class="col">
                                <input type="text" name="category_name" class="form-control" id="category_name">
                                <span class="text-message category-name-msg"></span>
                            </div>
                        </div>  
                        <div class="row mb-3 align-items-center edit">  
                            <label class="col-form-label col-sm-4">Trạng thái</label>
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
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" id="saveModalBtn" class="btn btn-success">Thêm danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ... -->
    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Category.js"></script>
</body>
</html>