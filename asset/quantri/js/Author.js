// Reset
document.getElementById('authorModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('authorForm').reset();
    let textMessage = document.querySelectorAll('.text-message');
    textMessage.forEach(element => {
        element.textContent = '';
    });
    location.reload();
});

$(document).ready(function() {
    const modalTitle = document.getElementById('authorModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    // open add form
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm tác giả';
        modalSaveBtn.textContent = 'Thêm tác giả';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        document.getElementById('authorForm').querySelector('.edit').style.display = 'none';
    });
   // open edit form
   $('.open_edit_form').click(function(e) {
        e.preventDefault();
        var author_id = $(this).closest('tr').find('.author_id').text();
        modalTitle.textContent = 'Chỉnh sửa tác giả';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        $.ajax({
            url: '../controller/quantri/AuthorController.php', // Replace with the actual PHP endpoint to fetch user details
            type: 'POST',
            data: {
                'action': 'edit_data',
                'author_id': author_id,
            },
            success: function(response){
                console.log(response);
                const obj = JSON.parse(response);
                $('#author_id').val(obj.idTG);
                $('#author_name').val(obj.tenTG);
                $('#author_email').val(obj.email);
                $('#status').val(obj.trangthai);
                // console.log(obj.trangthai);
                if(parseInt(obj.trangthai)){
                    $('#status').prop('checked', true);
                    $('#switch-label').text('Đang hoạt động');
                }
                else {
                    $('#status').prop('checked', false);
                    $('#switch-label').text('Bị khóa');
                }
                document.getElementById('authorForm').querySelector('.edit').style.display = 'flex';
            },
        });
    });

    function AuthorValidateError(){
        // Clear error message
        $('.text-message.author-name-msg').text('');
        $('.text-message.author-email-msg').text('');

         // Validate form
         var ten = $('#author_name').val().trim();
         var email = $('#author_email').val().trim();
         var error = false;
         const regexEmail =
             /^(?=.{1,255}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
     
         if (ten === '') {
             $('.text-message.author-name-msg').text('Tên tác giả không được để trống');
             error = true;
         }
         else if (email === '') {
             $('.text-message.author-email-msg').text('Email không được để trống');
             error = true;
         }
         else if (!regexEmail.test(email)) {
             $('.text-message.author-email-msg').text('Vui lòng nhập đúng định dạng của email (Ví dụ: abc@example.com)');
             error = true;
         }
         return error;
    }

    $('#authorForm').submit(function(event) {
        // Prevent the default form submission
        event.preventDefault();
        
        if(!AuthorValidateError()){
            // Serialize form data
            var formData = new FormData( $('#authorForm')[0]);
            // AJAX request to handle form submission
            $.ajax({
                url: '../controller/quantri/AuthorController.php', // URL to handle form submission
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if(obj.success){
                        if(obj.btn == 'add') {
                            toast({
                                title: 'Thành công',
                                message: 'Thêm tác giả thành công',
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: 'Cập nhật tác giả thành công',
                                type: 'success',
                                duration: 3000
                            });
                        }
                    }
                    else toast({
                        title: 'Lỗi',
                        message: 'Email đã tồn tại',
                        type: 'error',
                        duration: 3000
                    });
                },
            });
        }
    });
});