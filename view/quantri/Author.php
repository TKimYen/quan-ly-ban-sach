
<main class="container pt-5">
        <!-- Page title -->
        <div class="row">
            <h1 class="page-title">QUẢN LÝ TÁC GIẢ</h1>
        </div>
        <!-- ... -->
        <!-- Page control -->
        <div class="row d-flex justify-content-between">
            <?php
                if(isset($_SESSION['function']['TG_them'])){
            ?>
            <div class="col-auto">
                <button class="btn btn-control open_add_form" 
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#authorModal"
                >
                    <i class="fa-regular fa-plus me-2"></i>
                    Thêm tác giả
                </button>
            </div>
            <?php
                }
            ?>
            <div class="col">
                <form action="">
                    <div class="input-group">
                    <input type="hidden" name="page" value="searchAuthor">
                        <input type="text" 
                                class="form-control" 
                                placeholder="Nhập id, tên tác giả" 
                                aria-label="Tìm kiếm tác giả" 
                                aria-describedby="search-bar"
                                name="kyw"
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
                            <th scope="col">Mã tác giả</th>
                            <th>Tên tác giả</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $authors = $result['paging'];
                         if($authors == null) {
                            echo '<tr><td colspan="4">Không có dữ liệu</td> </tr>';
                            echo '</tbody></table></div></div>';
                        } else {
                        echo '<input type="hidden" name="curr_page" class="curr_page" value="'.$paging->curr_page.'">';
                        for($i=$paging->start; $i<$paging->start+$paging->num_per_page && $i<$paging->total_records; $i++){
                            $author = $authors[$i];
                        ?>
                        <tr>
                            <td class="author_id"><?=$author->getIdTG()?></td>
                            <td class ="author_name"><?=$author->getTenTG()?></td>
                            <td class="author_email"><?=$author->getEmail()?></td>
                            <td>
                                <?php
                                    if($author->getTrangthai())
                                        echo '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>';
                                    else
                                        echo '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                                ?>
                            </td>
                            <?php
                                if(isset($_SESSION['function']['TG_sua'])){
                            ?>
                            <td>
                                <button class="btn open-edit-modal fs-5 open_edit_form"
                                        data-bs-toggle="modal"
                                        data-bs-target="#authorModal"
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
    <div class="modal fade" id="authorModal" tabindex="-1" aria-labelledby="authorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-success" id="authorModalLabel">Thêm tác giả</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="authorForm">
                    <input type="hidden" name="author_id" id="author_id" value="">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="author_name" class="col-form-label col-sm-3">Tên tác giả</label>
                            <div class="col-sm-9">
                                <input type="text" name="author_name" class="form-control" id="author_name">
                                <span class="text-message author-name-msg"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="author_name" class="col-form-label col-sm-3">Email</label>
                            <div class="col-sm-9">
                                <input type="text" name="author_email" class="form-control" id="author_email">
                                <span class="text-message author-email-msg"></span>
                            </div>
                        </div>
                        <div class="row align-items-center edit">
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
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="" id="submit_btn">
                        <button type="submit" id="saveModalBtn" class="btn btn-success">Thêm tác giả</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ... -->

    <!-- Link JS ở chỗ này nè!!! -->
    <script src="../asset/quantri/js/Author.js"></script>
</body>
</html>