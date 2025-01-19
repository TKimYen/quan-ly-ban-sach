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

    // Cập nhật số lượng sách trong giỏ hàng
    $('.item-quantity-btn').on('click', function () {
        let quantity = parseInt($(this).siblings('.item-quantity').val());
        let inStock = parseInt($(this).siblings('.item-quantity').data('instock'));
        let index = $(this).siblings('.item-quantity').data('index');

        if ($(this).hasClass('btn-subtract') && quantity > 1) {
            quantity--;
            console.log(quantity);
            
        } else if ($(this).hasClass('btn-add')) {
            if (quantity < inStock) {
                quantity++;
            } else {
                toast({
                    title: 'Cảnh báo',
                    message: 'Số lượng sách không đủ! Còn lại ' + inStock + ' cuốn',
                    type: 'warning'
                });
            }
        }
        $(this).siblings('.item-quantity').val(quantity);
        updateCart(index, quantity);
    });

    $('.item-quantity').on('change', function () {
        let quantity = parseInt($(this).val());
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