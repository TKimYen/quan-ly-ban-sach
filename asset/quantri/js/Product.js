document.getElementById("searchProduct_form").onsubmit = function (e) {
    // Perform validation
    let alert = '';
    let price_min = document.getElementById('price-min').value;
    let price_max = document.getElementById('price-max').value;
    if (price_min !== '' && isNaN(price_min))
        alert = 'Giá bắt đầu phải là một số hợp lệ';
    else if (price_max !== '' && isNaN(price_max))
        alert = 'Giá kết thúc phải là một số hợp lệ';
    else if (price_min !== '' && price_max !== '') 
        if (parseFloat(price_max) < parseFloat(price_min)) {
            alert = 'Giá bắt đầu phải nhỏ hơn giá kết thúc';
        }

    if(alert != ''){
        e.preventDefault();
        toast({
            title: 'Lỗi',
            message: alert,
            type: 'error',
            duration: 3000
        });
    }
    // If validation passes, do nothing and let the form submit
  };

// Reset
document.getElementById('openSelectAuthorModalBtn').addEventListener('click', function (e) {
    const selectAuthorModal = new bootstrap.Modal(document.getElementById('selectAuthorModal'));
    selectAuthorModal.show(); // Hiển thị modal chọn tác giả
});

document.getElementById('productModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('productForm').reset();
    let textMessage = document.querySelectorAll('.text-message');
    textMessage.forEach(element => {
        element.textContent = '';
    });
    location.reload();
});

$(document).ready(function () {
    var authors = [];

    $('#upload-img').on('change', function (event) {
        let url = URL.createObjectURL(event.target.files[0]);
        $('#img-preview').attr('src', url);
    });

    const modalTitle = document.getElementById('productModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    let submit_btn = document.getElementById('submit_btn');

    $('.open_add_form').on('click', function () {
        modalTitle.textContent = 'Thêm sản phẩm';
        modalSaveBtn.textContent = 'Thêm sản phẩm';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        document.getElementById('productForm').querySelectorAll('.edit').forEach(element => {
            element.style.display = 'none';
        });
    });

    $('.open_edit_form').on('click', function () {
        modalTitle.textContent = 'Chỉnh sửa sản phẩm';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        let product_id = $(this).closest('tr').find('.product_id').text();
        console.log('hello');
        $.ajax({
            url: "../controller/quantri/ProductController.php",
            type: "POST",
            data: {
                'action': 'edit_data',
                'product_id': product_id
            },
            success: function (response) {
                console.log(response);
                const obj= JSON.parse(response);
                if (obj.status == "success") {
                    data = obj.data;
                    $('#productForm input[name="product-id"]').val(data.idSach);
                    $('#img-preview').prop('src', `../asset/uploads/${data.hinhanh}`);
                    $('#productForm input[name="product-name"]').val(data.tuasach);
                    $('#productForm input[name="product-publisher"]').val(data.NXB);
                    $('#productForm select[name="product-supplier"]').hide();
                    $('#tenncc').html(data.tenNCC);
                    $('#productForm input[name="idNCC"]').val(data.idNCC);
                    $('#productForm select[name="product-category"]').val(data.idTL);
                    $('#productForm input[name="product-original-price"]').val(data.giabia);
                    $('#productForm input[name="product-sale-price"]').val(data.giaban);
                    $('#productForm input[name="product-publish-year"]').val(parseInt(data.namXB));
                    $('#productForm input[name="product-weight"]').val(data.trongluong);
                    // append discount
                    $('#productForm select[name="product-discount"]').html('');
                    // dang dien ra
                    let option = '';
                    let discountSelected = obj.discountSelected;
                    let discounts = obj.discounts;
                    if(data.giaban != data.giabia){
                        option += '<option value="'+discountSelected['idMGG']+'">'+discountSelected['phantram']+'</option>';
                    }
                    else{
                        option+= '<option value="">Chọn mã giảm giá</option>';
                        for(let i=0; i<discounts.length; i++)
                            option+='<option value="'+discounts[i]['idMGG']+'">'+discounts[i]['phantram']+'% (từ '+discounts[i]['ngaybatdau']+' đến '+discounts[i]['ngayketthuc']+')'+'</option>';
                    }
                    $('#productForm select[name="product-discount"]').html(option);
                    
                    $('#productForm select[name="product-discount"]').val(data.idMGG === null ? "" : data.idMGG);
                    $('#productForm textarea[name="product-description"]').val(data.mota);
                    $('#status').prop('checked', data.trangthai == 1 ? true : false);
                    $('label[for="status"]').text(data.trangthai == 1 ? "Đang bán" : "Bị ẩn");
                    console.log(data.authors);
                    data.authors.forEach(author => {
                        authors.push(author.idTG);
                    });

                    $('.select-author-checkbox').each(function () {
                        if (authors.includes(parseInt($(this).val()))) {
                            $(this).prop('checked', true);
                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '#saveSubModalBtn', function () {
        // Reset authors
        authors.length = 0;

        $('.select-author-checkbox').each(function () {
            if ($(this).is(':checked')) {
                authors.push($(this).val());
            }
        });
        if (authors.length === 0) {
            toast({
                title: 'Thất bại!',
                message: 'Vui lòng chọn ít nhất một tác giả!',
                type: 'error',
                duration: 3000
            });
            return;
        }
        toast({
            title: 'Thành công!',
            message: 'Chọn tác giả thành công!',
            type: 'success',
            duration: 3000
        });
        console.log(authors);
    })

    function formValidate(action) {
        let img = $('#productForm input[name="product-img"]').val();
        let name = $('#productForm input[name="product-name"]').val();
        let publisher = $('#productForm input[name="product-publisher"]').val();
        let supplier = $('#productForm select[name="product-supplier"]').val();
        let author = authors;
        let category = $('#productForm select[name="product-category"]').val();
        let original_price = $('#productForm input[name="product-original-price"]').val();
        // let sale_price = $('#productForm input[name="product-sale-price"]').val();
        let publish_year = $('#productForm input[name="product-publish-year"]').val();
        let weight = $('#productForm input[name="product-weight"]').val();
        let description = $('#productForm textarea[name="product-description"]').val().trim();

        let isValid = true;

        if (action === 'add' && img === '') {
            $('.text-message.product-img-msg').text("Vui lòng chọn ảnh cho sản phẩm!");
            isValid = false;
        } else {
            $('.text-message.product-img-msg').text("");
        }

        if (name === '') {
            $('.text-message.product-name-msg').text("Vui lòng nhập tên sản phẩm!");
            isValid = false;
        } else {
            $('.text-message.product-name-msg').text("");
        }

        if (publisher === '') {
            $('.text-message.product-publisher-msg').text("Vui lòng nhập nhà xuất bản!");
            isValid = false;
        } else {
            $('.text-message.product-publisher-msg').text("");
        }

        if (supplier === '' && action === 'add') {
            $('.text-message.product-supplier-msg').text("Vui lòng chọn nhà cung cấp!");
            isValid = false;
        } else {
            $('.text-message.product-supplier-msg').text("");
        }

        if (author.length === 0) {
            $('.text-message.product-author-msg').text("Vui lòng chọn tác giả!");
            isValid = false;
        } else {
            $('.text-message.product-author-msg').text("");
        }

        if (category === '') {
            $('.text-message.product-category-msg').text("Vui lòng chọn thể loại!");
            isValid = false;
        } else {
            $('.text-message.product-category-msg').text("");
        }

        if (original_price === '') {
            $('.text-message.product-original-price-msg').text("Vui lòng nhập giá bìa!");
            isValid = false;
        } else if (isNaN(original_price)) {
            $('.text-message.product-original-price-msg').text("Giá bìa không hợp lệ!");
            isValid = false;
        } else if (original_price <= 0) {
            $('.text-message.product-original-price-msg').text("Giá bìa không hợp lệ!");
            isValid = false;
        } else {
            $('.text-message.product-original-price-msg').text("");
        }
        
        let currentYear = new Date().getFullYear();
        if (publish_year === '') {
            $('.text-message.product-publish-year-msg').text("Vui lòng nhập năm xuất bản!");
            isValid = false;
        } else if (isNaN(publish_year) || publish_year<0 || publish_year.length !== 4) {
            $('.text-message.product-publish-year-msg').text("Năm xuất bản không hợp lệ!");
            isValid = false;
        } else if (parseInt(publish_year) > currentYear) { 
            $('.text-message.product-publish-year-msg').text("Năm xuất bản không được lớn hơn năm hiện tại!");
            isValid = false;
        } else {
            $('.text-message.product-publish-year-msg').text("");
        }

        if (weight === '') {
            $('.text-message.product-weight-msg').text("Vui lòng nhập trọng lượng!");
            isValid = false;
        } else if (isNaN(weight)) {
            $('.text-message.product-weight-msg').text("Trọng lượng không hợp lệ!");
            isValid = false;
        } else if (weight <= 0) {
            $('.text-message.product-weight-msg').text("Trọng lượng không hợp lệ!");
            isValid = false;
        } else {
            $('.text-message.product-weight-msg').text("");
        }

        if (description === '') {
            $('.text-message.product-description-msg').text("Vui lòng nhập mô tả!");
            isValid = false;
        } else {
            $('.text-message.product-description-msg').text("");
        }

        return isValid;
    }

    function concatAuthors(authors) {
        let authorList = '';

        authors.forEach(author => {
            authorList += author.tenTG + ', ';
        });
        return authorList.slice(0, -2); // Bỏ ', ' ở cuối
    }

    function renderProductDetail(data) {
        let productDetailHtml = `
        <div class="row">
            <div class="col-4 modal-body-left px-3">
                <div class="img-preview-container">
                    <div class="upload-img-preview">
                        <img src="../asset/uploads/${data.hinhanh}" alt="" id="product-img-preview" class="img-preview view-img">
                    </div>
                </div>
            </div>
            <div class="col-8 modal-body-right px-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Tên sách</span>
                        <span class="detail-value text-end w-50">${data.tuasach}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Nhà xuất bản</span>
                        <span class="detail-value text-end w-50">${data.NXB}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Nhà cung cấp</span>
                        <span class="detail-value text-end w-50">${data.tenNCC}</span>
                    </li>
                    <li class="list-group-item d-flex flex-column">
                        <span class="fw-bold">Tác giả</span>
                        <textarea disabled class="detail-value w-100 rounded-2 mt-2" rows="3">${concatAuthors(data.authors)}</textarea>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Thể loại</span>
                        <span class="detail-value text-end w-50">${data.tenTL}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Giá bìa</span>
                        <span class="detail-value text-end w-50">${data.giabia.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Giá bán</span>
                        <span class="detail-value text-end w-50">${data.giaban.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Năm xuất bản</span>
                        <span class="detail-value text-end w-50">${data.namXB}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Trọng lượng</span>
                        <span class="detail-value text-end w-50">${data.trongluong}g</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Giảm giá</span>
                        <span class="detail-value text-end w-50">${data.discount == null ? 0 : data.discount}%</span>
                    </li>
                    <li class="list-group-item d-flex flex-column">
                        <span class="fw-bold">Mô tả</span>
                        <textarea disabled class="detail-value w-100 rounded-2 mt-2" rows="5">${data.mota}</textarea>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Trạng thái</span>
                        <span class="detail-value text-end w-50">${data.trangthai === 1 ? "Đang bán" : "Bị ẩn"}</span>
                    </li>
                </ul>
            </div>
        </div>
        `;
        document.getElementById('productViewModal').querySelector('.modal-body').innerHTML = productDetailHtml;
    }

    $('.open_view_form').on('click', function () {
        let productId = $(this).closest('tr').find('.product_id').text();

        $.ajax({
            url: '../controller/quantri/ProductController.php',
            type: 'POST',
            data: {
                'action': 'getProductDetail',
                'product_id': productId
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == "success") {
                    renderProductDetail(response.data);
                } else {
                    toast({
                        title: 'Thất bại!',
                        message: 'Lấy thông tin sản phẩm thất bại!',
                        type: 'error',
                        duration: 3000
                    })
                }
            }
        });
    });

    $('#productForm').on('submit', function (event) {
        event.preventDefault();
        let action = submit_btn.value === "submit_btn_add" ? "add" : "edit";
        if (formValidate(action)) {
            let formData = new FormData($('#productForm')[0]);
            formData.append('product-authors', authors);
            $.ajax({
                url: '../controller/quantri/ProductController.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if (obj.btn == "add" && obj.success) {
                        toast({
                            title: 'Thành công!',
                            message: 'Thêm sản phẩm thành công!',
                            type: 'success',
                            duration: 3000
                        });
                    } else if (obj.btn == "add" && !obj.success) {
                        toast({
                            title: 'Thất bại!',
                            message: 'Thông tin sản phẩm trùng lặp!',
                            type: 'error',
                            duration: 3000
                        });
                    } else if (obj.btn == "update" && obj.success) {
                        toast({
                            title: 'Thành công!',
                            message: 'Cập nhật sản phẩm thành công!',
                            type: 'success',
                            duration: 3000
                        });
                    } else {
                        toast({
                            title: 'Thất bại!',
                            message: 'Thông tin sản phẩm trùng lặp!',
                            type: 'error',
                            duration: 3000
                        });
                    }
                }
            })
        } else {
            toast({
                title: 'Thất bại!',
                message: 'Vui lòng kiểm tra lại thông tin!',
                type: 'error',
                duration: 3000
            });
        }
    })
});