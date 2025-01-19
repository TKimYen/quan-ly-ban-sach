// Reset
let textMessage = document.querySelectorAll('.text-message');
function emptyMsg(){
    // Loop through each element and clear its content
    textMessage.forEach(element => {
        element.textContent = ''; // Clear text content
    });
}
document.getElementById('discountModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('discountForm').reset();
    emptyMsg();
    location.reload();
});
    


/* function validate discount form */
function formValidateDiscount() {
     // validate form
    var phantram = $('#discountForm input[name="discount-percent"]').val();
    var ngaybatdau = $('#discountForm input[name="discount-date-start"]').val();
    var ngayketthuc = $('#discountForm input[name="discount-date-end"]').val();

    var discount_percent_msg = $('.discount-percent-msg');
    var discount_date_start_msg = $('.discount-date-start-msg');
    var discount_date_end_msg = $('.discount-date-end-msg');
    //Kiểm tra hợp lệ
    var curr_date = new Date();
    //phantram

    if(phantram == ""){
        discount_percent_msg.html("Phần trăm không được để trống");
        return false;
    }
    if(phantram <= 0 || phantram>=100 || isNaN(phantram)) {   //nếu tên rỗng
        discount_percent_msg.html("Phần trăm giảm phải lớn hơn 0 và bé hơn 100");
        return false;
    }

    //thoi gian
    let start = new Date(ngaybatdau);
    let end = new Date(ngayketthuc);
    start.setHours(0, 0, 0, 0);
    end.setHours(0,0,0,0);
    curr_date.setHours(0,0,0,0);    

    if(ngaybatdau == ""){
        discount_date_start_msg.html("Thời gian không để trống!");
        return false;
    }
    if(ngayketthuc == ""){
        discount_date_end_msg.html("Thời gian không để trống!");
        return false;
    }

    if(start <= curr_date){
        discount_date_start_msg.html( "Ngày bắt đầu phải lớn hơn ngày hiện tại!");
        return false;
    }

    if(ngaybatdau >= ngayketthuc){
        discount_date_end_msg.html("Ngày kết thúc phải lớn hơn bắt đầu!");
        return false;
    }

    return true;
}
/* function validate discount form */
// ------------------------------------------------------------------------------------

/* add-data form */
$(document).ready(function() {
    const modalTitle = document.getElementById('discountModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    /* Start: add form */
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm mã giảm giá';
        modalSaveBtn.textContent = 'Thêm mã giảm giá';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
   });
    /* End: add form */

    /* Start: edit form */
    $('.open_edit_form').click(function(e) {
        e.preventDefault();

        var discount_id = $(this).closest('tr').find('.discount_id').text();
        modalTitle.textContent = 'Sửa mã giảm giá';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        $.ajax({
            url: '../controller/quantri/DiscountController.php', // Replace with the actual PHP endpoint to fetch user details
            type: 'POST',
            data: {
                'action': 'edit_data',
                'discount_id': discount_id,
            },
            success: function(response){
                console.log(response);
                const obj = JSON.parse(response);
                $('#discountForm input[name="discount_id"]').val(obj.idMGG);
                $('#discountForm input[name="discount-percent"]').val(obj.phantram);
                $('#discountForm input[name="discount-date-start"]').val(obj.ngaybatdau);
                $('#discountForm input[name="discount-date-end"]').val(obj.ngayketthuc);
            },
        });
    });

    $('#discountForm').submit(function(event) {
        // Prevent the default form submission
        event.preventDefault();
        emptyMsg();
        if(formValidateDiscount()){
            // Serialize form data
            var formData = new FormData( $('#discountForm')[0]);
            // AJAX request to handle form submission
            $.ajax({
                url: '../controller/quantri/DiscountController.php', // URL to handle form submission
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if(obj.success){
                        if(obj.btn == 'add'){
                            toast({
                                title: 'Thành công',
                                message: 'Thêm mã giảm giá thành công',
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: 'Cập nhật mã giảm giá thành công',
                                type: 'success',
                                duration: 3000
                            });
                        }
                    } else {
                        if(obj.btn == 'add'){
                            toast({
                                title: 'Lỗi',
                                message: 'Thêm mã giảm giá thất bại',
                                type: 'error',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Lỗi',
                                message: 'Cập nhật mã giảm giá thất bại',
                                type: 'error',
                                duration: 3000
                            });
                        }
                    }
                },
            });
        }
    });
        /* update data */

    /* Start: lock */
    $(document).on('click', '.lock_discount', function(e) {
        // Display the form as a pop-up
        var discount_id = $(this).closest('tr').find('.discount_id').text();
        var discount_status = $(this).closest('tr').find('.discount_status');
        $.ajax({
            url: '../controller/quantri/DiscountController.php', // Replace with the actual PHP endpoint to fetch discount details
            type: 'POST',
            data: {
                'action': 'lock_discount',
                'discount_id': discount_id,
            },
            success: function(response){
                const obj = JSON.parse(response);
                if(obj.success){
                    toast({
                        title: 'Thành công',
                        message: 'Hủy mã giảm giá thành công',
                        type: 'success',
                        duration: 3000
                    });
                    location.reload();
                }
            },
        });
   });
    /* End: lock */
});