// Reset
document.getElementById('permissionModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('permissionForm').reset();
    let textMessage = document.querySelectorAll('.text-message');
    textMessage.forEach(element => {
        element.textContent = '';
    });
    location.reload();
});

$(document).ready(function() {
    const modalTitle = document.getElementById('permissionModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    // open add form
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm nhóm quyền';
        modalSaveBtn.textContent = 'Thêm nhóm quyền';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        // document.getElementById('permissionForm').querySelector('.edit').style.display = 'none';
    });
    $('.open_view_form').click(function() {
        modalTitle.textContent = 'Chi tiết nhóm quyền';
        modalSaveBtn.style.display = 'none';
        var role_id = $(this).closest('tr').find('.role_id').text();
        $.ajax({
            url: '../controller/quantri/RoleController.php', // Replace with the actual PHP endpoint to fetch user details
            type: 'POST',
            data: {
                'action': 'view_data',
                'role_id': role_id,
            },
            success: function(response){
                console.log(response);
                const obj = JSON.parse(response);
                var role = obj.role;
                var role_detail = obj.role_detail;
                //Display role name
                $('#permissionGroupName').val(role.tenNQ);

                //Display permission
                for(var i=0; i<role_detail.length; i++)
                    $('#permissionForm input[name="'+role_detail[i].tenCN+'"]').prop('checked', true);
                
                //Disable all input field
                var inputs = document.querySelectorAll('#permissionForm input');
                inputs.forEach(input => {
                    input.setAttribute('disabled', true);
                });
            },
        });
    });
   // open edit form
   $('.open_edit_form').click(function(e) {
        e.preventDefault();
        modalTitle.textContent = 'Chỉnh sửa nhóm quyền';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        var role_id = $(this).closest('tr').find('.role_id').text();
        $.ajax({
            url: '../controller/quantri/RoleController.php', // Replace with the actual PHP endpoint to fetch user details
            type: 'POST',
            data: {
                'action': 'view_data',
                'role_id': role_id,
            },
            success: function(response){
                console.log(response);
                const obj = JSON.parse(response);
                var role = obj.role;
                var role_detail = obj.role_detail;
                //Display role name
                $('#permissionGroupName').val(role.tenNQ);
                $('#idNQ').val(role.idNQ);

                //Display permission
                for(var i=0; i<role_detail.length; i++)
                    $('#permissionForm input[name="'+role_detail[i].tenCN+'"]').prop('checked', true);
            },
        });
    });

    $('#permissionForm').submit(function(event) {
        // Prevent the default form submission
        event.preventDefault();
        
        // validate form
        var tenNQ = $('#permissionGroupName').val();
        if(tenNQ != ''){
            // Serialize form data
            var formData = new FormData( $('#permissionForm')[0]);
            // AJAX request to handle form submission
            $.ajax({
                url: '../controller/quantri/RoleController.php', // URL to handle form submission
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
                                message: 'Thêm nhóm quyền thành công',
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: 'Cập nhật nhóm quyền thành công',
                                type: 'success',
                                duration: 3000
                            });
                        }
                    }
                    else toast({
                        title: 'Lỗi',
                        message: obj.msg,
                        type: 'error',
                        duration: 3000
                    });
                },
            });
        }
        else {
            $('.text-message.role-name-msg').text('Tên nhóm quyền không được để trống');
        }
    });

    // lock-role
    $(document).on('click', '.lock_role', function(e) {
        console.log($(this));
        var lockElement = $(this).closest('span');
        var statusElement = $(this).closest('tr').find('.status');
        var role_id = $(this).closest('tr').find('.role_id').text();
        $.ajax({
            url: '../controller/quantri/RoleController.php', // Replace with the actual PHP endpoint to fetch user details
            type: 'POST',
            data: {
                'action': 'lock_role',
                'role_id': role_id,
            },
            success: function(response){
                console.log(response);
                const obj = JSON.parse(response);
                if(obj.success){
                    lockElement.html(
                        '<button class="btn fs-5 unlock_role">'+
                        '<i class="fa-regular fa-unlock"></i>'+
                        '</button>');
                    statusElement.html(
                        '<span class="bagde rounded-2 text-white bg-secondary p-2">Bị khóa</span>'
                    );
                    toast({
                        title: 'Thành công',
                        message: 'Khoá nhóm quyền thành công',
                        type: 'success',
                        duration: 3000
                    });
                }
            },
        });
    });

    // unlock-role
    $(document).on('click', '.unlock_role', function(e) {
        e.preventDefault();
        var lockElement = $(this).closest('span');
        var statusElement = $(this).closest('tr').find('.status');
        var role_id = $(this).closest('tr').find('.role_id').text();
        $.ajax({
            url: '../controller/quantri/RoleController.php', // Replace with the actual PHP endpoint to fetch user details
            type: 'POST',
            data: {
                'action': 'unlock_role',
                'role_id': role_id,
            },
            success: function(response){
                const obj = JSON.parse(response);
                if(obj.success){
                    lockElement.html(
                        '<button class="btn fs-5 lock_role">'+
                        '<i class="fa-regular fa-lock"></i>'+
                        '</button>');
                    statusElement.html(
                        '<span class="bagde rounded-2 text-white bg-success p-2">Hoạt động</span>'
                    );
                    toast({
                        title: 'Thành công',
                        message: 'Mở khoá nhóm quyền thành công',
                        type: 'success',
                        duration: 3000
                    });
                }
            },
        });
    });
});