$(document).ready(function () {

    // Thêm sách vào giỏ hàng
    $(".btn.add-to-cart-btn").click(function () {
        $.ajax({
            url: 'controller/client/HomeController.php',
            type: 'get',
            data: {
                page: 'add-to-cart',
                idSach: $(this).data("id")
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {
                    toast({
                        title: 'Thành công',
                        message: response.message,
                        type: response.status
                    });
                } else {
                    toast({
                        title: 'Thất bại',
                        message: response.message,
                        type: response.status
                    });
                }
            }
        })
    });

    $(".btn.add-to-cart-btn.notSignIn").click(function () {
        window.location.href = '?page=login';
    });

    // Xóa sách khỏi giỏ hàng
    $('.delete-cart-item').on('click', function () {
        $.ajax({
            url: 'controller/client/HomeController.php',
            type: 'get',
            data: {
                page: 'delete-cart-item',
                index: $(this).data("index")
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {
                    window.location.reload();
                } else {
                    toast({
                        title: 'Thất bại',
                        message: response.message,
                        type: response.status
                    });
                }
            }
        });
    });


    $('.item-quantity-btn').on('click', function () {
        let quantity = parseInt($(this).siblings('.item-quantity').val());
        let inStock = parseInt($(this).siblings('.item-quantity').data('instock'));
        let index = $(this).siblings('.item-quantity').data('index');
        
        let noti = $(this).closest('.input-quantity').find('.noti-quantity');
        
        // Reset thông báo lỗi mỗi khi người dùng thay đổi số lượng
        noti.text('').removeClass('text-danger');
    
        if ($(this).hasClass('btn-subtract') && quantity > 1) {
            quantity--;
        } else if ($(this).hasClass('btn-add')) {
            if (quantity < inStock) {
                quantity++;
            } else {
                noti.text('Số lượng sách không đủ! Còn lại ' + inStock + ' cuốn').addClass('text-danger');
            }
        }
    
        // Cập nhật giá trị trong input
        $(this).siblings('.item-quantity').val(quantity);
        
        // Cập nhật giỏ hàng
        updateCart(index, quantity);
    
        // Kiểm tra nếu có thông báo lỗi (noti) nào trong giỏ hàng
        checkForErrors();
    });

    // Kiểm tra lỗi thông qua thông báo lỗi
    function checkForErrors() {
        let hasError = false;
        
        $('.noti-quantity').each(function() {
            if ($(this).text().trim() !== '') {
                hasError = true;
                return false;
            }
        });
    
        // Disable nút thanh toán nếu có lỗi
        // if (hasError) {
        //     $('#checkout-button').prop('disabled', true);
        // } else {
        //     $('#checkout-button').prop('disabled', false);
        // }

        if (hasError) {
            $('#checkout-button').prop('disabled', true);
            print("vppww")
            $('.noti-checkout').text('Số lượng không hợp lệ').addClass('text-danger');
        } else {
            $('#checkout-button').prop('disabled', false);
            print("pqweqwr")
            $('.noti-checkout').text('').removeClass('text-danger');
        }
    }

    // Xử lý hành động thanh toán
    window.handleCheckout = function() {
        let hasError = false;
        
        $('.noti-quantity').each(function() {
            if ($(this).text().trim() !== '') {
                hasError = true;
                return false;
            }
        });
    
        if (hasError) {
            print("Print Há ")
            $('.noti-checkout').text('Số lượng không hợp lệ').addClass('text-danger');
            alert("Vui lòng kiểm tra lại số lượng sản phẩm trước khi thanh toán.");
        } else {
            window.location.href = "?page=checkout-address";
        }
    };
    

    $('#checkout-link').on('click', function (e) {
    // Lấy giá trị số lượng từ phần tử .item-quantity
    let quantity = parseInt($('.item-quantity').val());  
    let inStock = parseInt($('.item-quantity').data('instock'));  // Lấy số lượng tồn kho từ data-instock của input

    // Kiểm tra các điều kiện lỗi
    if (quantity === '' || quantity < 1) {
        toast({
            title: 'Cảnh báo',
            message: 'Số lượng không được nhỏ hơn 1!',
            type: 'warning'
        });
        quantity = 1;  // Đặt lại giá trị của quantity về 1 nếu có lỗi
        e.preventDefault();  // Ngừng hành động mặc định (không chuyển trang)
    } else if (quantity > inStock) {
        quantity = inStock;
        toast({
            title: 'Cảnh báo',
            message: 'Số lượng sách không đủ! Còn lại ' + inStock + ' cuốn',
            type: 'warning'
        });
        e.preventDefault();  // Ngừng hành động mặc định (không chuyển trang)
    } else if (isNaN(quantity)) {
        quantity = 1;
        toast({
            title: 'Cảnh báo',
            message: 'Số lượng không hợp lệ!',
            type: 'warning'
        });
        e.preventDefault();  // Ngừng hành động mặc định (không chuyển trang)
    }
    
    // Cập nhật lại giá trị số lượng trong input (nếu có thay đổi)
    $('.item-quantity').val(quantity);
});

    
    

    $('.item-quantity').on('change', function () {
        let quantity = parseInt($('.item-quantity').val());  
        let inStock = parseInt($(this).data('instock'));
        let index = $(this).data('index');

        if (quantity == '' || quantity < 1) {
            toast({
                title: 'Cảnh báo',
                message: 'Số lượng không được nhỏ hơn 1!',
                type: 'warning'
            });
            quantity = 1;
        } else if (quantity > inStock) {
            quantity = inStock;
            toast({
                title: 'Cảnh báo',
                message: 'Số lượng sách không đủ! Còn lại ' + inStock + ' cuốn',
                type: 'warning'
            });
        } else if (isNaN(quantity)) {
            quantity = 1;
            toast({
                title: 'Cảnh báo',
                message: 'Số lượng không hợp lệ!',
                type: 'warning'
            });
        }
        $(this).val(quantity);
        updateCart(index, quantity);
    });


    function updateCart(index, quantity) {
        $.ajax({
            url: 'controller/client/HomeController.php',
            type: 'get',
            data: {
                page: 'update-cart',
                index: index,
                quantity: quantity
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {
                    let newTotalQuantity = response.data.reduce((accumulator, currentValue) => accumulator + currentValue.soluong, 0);
                    let newTotalPrice = response.data.reduce((accumulator, currentValue) => accumulator + currentValue.soluong * currentValue.giaban, 0);

                    $('#cart-total-quantity').text(newTotalQuantity);
                    $('#cart-total-price').text(newTotalPrice.toLocaleString('vi-VN'));
                } else {
                    toast({
                        title: 'Thất bại',
                        message: response.message,
                        type: response.status
                    });
                }
            }
        })
    }
})


