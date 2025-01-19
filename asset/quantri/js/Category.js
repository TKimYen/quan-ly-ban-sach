// 3
// Reset
document.getElementById('categoryModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('categoryForm').reset();
    let textMessage = document.querySelectorAll('.text-message');
    textMessage.forEach(element => {
        element.textContent = '';
    });
    location.reload();
});

$(document).ready(function() {
    const modalTitle = document.getElementById('categoryModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    // open add form
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm danh mục';
        modalSaveBtn.textContent = 'Thêm danh mục';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        document.getElementById('categoryForm').querySelector('.edit').style.display = 'none';
    });
   // open edit form
   $('.open_edit_form').click(function(e) {
        e.preventDefault();
        // this = open_edit_form
        // .closest('tr') tìm kiếm phần tử cha gần nhất có thẻ <tr> (table row) từ phần tử hiện tại (this)        
        // .find('.category_id') sẽ tìm kiếm phần tử con bên trong hàng đó có class author_id.
        // .text() sẽ lấy nội dung văn bản của phần tử được tìm thấy
        var category_id = $(this).closest('tr').find('.category_id').text();
        modalTitle.textContent = 'Chỉnh sửa danh mục';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        $.ajax({
            url: '../controller/quantri/CategoryController.php',
            type: 'POST',
            data: {
                'action': 'edit_data',
                'category_id': category_id,
            },
            success: function(response){
                const obj = JSON.parse(response);
                $('#category_id').val(obj.idTL);
                // console.log(obj.tenTL);
                $('#category_name').val(obj.tenTL);
                if(parseInt(obj.trangthai)){
                    $('#status').prop('checked', true);
                    $('#switch-label').text('Đang hoạt động');
                }
                else {
                    $('#status').prop('checked', false);
                    $('#switch-label').text('Bị khóa');
                }
                document.getElementById('categoryForm').querySelector('.edit').style.display = 'flex';
            },
        });
    });

    $('#categoryForm').submit(function(event) {
        event.preventDefault();
        var ten = $('#category_name').val();
        if(ten.trim() != "") {
            var formData = new FormData( $('#categoryForm')[0]);
            //processData: false Không tự động chuyển đổi dữ liệu thành chuỗi query 
            //contentType: false Không đặt kiểu nội dung, điều này cho phép jQuery tự động thiết lập nội dung cho yêu cầu
            $.ajax({
                url: '../controller/quantri/CategoryController.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // In ra phản hồi từ server 
                    console.log(response);
                    const obj = JSON.parse(response);
                    // Chuyển đổi chuỗi JSON từ server thành đối tượng JavaScript.
                    if(obj.success){
                        if(obj.btn == 'add') {
                            toast({
                                title: 'Thành công',
                                message: 'Thêm danh mục thành công',
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: 'Cập nhật danh mục thành công',
                                type: 'success',
                                duration: 3000
                            });
                        }
                    }
                    else {  
                        if (obj.btn == 'add') {
                            $('.text-message.category-name-msg').text('Danh mục đã tồn tại');
                        } else {
                            toast({
                                title: 'Lỗi',
                                message: 'Cập nhật danh mục thất bại',
                                type: 'error',
                                duration: 3000
                            });
                        }
                    }
                },
            });
        }
        else {
            $('.text-message.category-name-msg').text('Tên danh mục không được để trống');
        }
    });
});