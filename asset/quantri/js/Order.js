// Reset
const modal = document.getElementById('orderModal');

document.getElementById('orderModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('orderForm').reset();
    // Chỉ reload khi mở edit-modal
    location.reload();
});

function view(order_id, editForm=false){
    $.ajax({
        url: '../controller/quantri/OrderController.php', // Replace with the actual PHP endpoint to fetch user details
        type: 'POST',
        data: {
            'action': 'edit_data',
            'order_id': order_id
        },
        success: function(response){
            console.log(response);
            const obj = JSON.parse(response);
            const order = obj.order;
            const details = obj.details;
            const nhanvien = obj.nhanvien;
            const khachhang = obj.khachhang;
            const products = obj.products;
            const trangthai = obj.trangthai;
            // hien thi thong tin cua don hang
            $('#idDH').html(order.idDH);
            $('#orderForm input[name="idDH"]').val(order.idDH);
            $('#khachhang').html(khachhang.tenTK);
            $('#dienthoai').html(khachhang.dienthoai);
            console.log(order.diachi);
            $('#diachi').val(order.diachi);
            order.phiship = order.phiship.toLocaleString(
                undefined, // leave undefined to use the visitor's browser 
                           // locale or a string like 'en-US' to override it.
                { maximumFractionDigits: 2 }
              ).replace(/,/g, '.');
              order.phiship +="đ";
            $('#phiship').html(order.phiship)
            $('#ngaytao').html(order.ngaytao);
            $('#ngaycapnhat').html(order.ngaycapnhat);
            order.tongtien = order.tongtien.toLocaleString(
                undefined, // leave undefined to use the visitor's browser 
                           // locale or a string like 'en-US' to override it.
                { maximumFractionDigits: 2 }
              ).replace(/,/g, '.');
              order.tongtien +="đ";
            $('#tongtien').html(order.tongtien);
            let phuong_thuc_tt = order.phuong_thuc_tt;
            if(phuong_thuc_tt == 'ck') phuong_thuc_tt = 'Chuyển khoản';
            else phuong_thuc_tt='Thanh toán tiền mặt';
            $('#pttt').html(phuong_thuc_tt);
            if(nhanvien != '') $('#nhanvien').html(nhanvien.idTK+"-"+nhanvien.tenTK);
            if(editForm){
                var option = "";
                switch (trangthai.tenTT) {
                    case "Đang giao":
                        option += '<option value="4">Đang giao</option>';
                        option += '<option value="5">Đã giao</option>';
                        break;
                    case "Chờ duyệt":
                        option += '<option value="1">Chờ duyệt</option>';
                        option += '<option value="3">Hủy bởi người bán</option>';
                        option += '<option value="4">Đang giao</option>';
                        break;
                }
                $('#status-option').append(option);
                $('#status-option').val(trangthai.idTT);
            }
            else  $('#trangthai').html(trangthai.tenTT);
            // hien thi chi tiet don hang
            let n = products.length;
            let tr;
            let td;
            let gialucdatFormat;
            let thanhtien;
            for(let i=0; i<n; i++){
                tr = $('<tr></tr>');

                td = $('<td></td>');
                td.text(products[i].idSach); 
                tr.append(td);

                td = $('<td></td>');
                td.text(products[i].tuasach); 
                tr.append(td);

                td = $('<td></td>');
                td.text(details[i].soluong); 
                tr.append(td);

                gialucdatFormat = details[i].gialucdat.toLocaleString(
                    undefined, // leave undefined to use the visitor's browser 
                               // locale or a string like 'en-US' to override it.
                    { maximumFractionDigits: 2 }
                  ).replace(/,/g, '.');
                gialucdatFormat +="đ";
                td = $('<td></td>');
                td.text(gialucdatFormat); 
                tr.append(td);
                
                thanhtien = details[i].soluong * details[i].gialucdat;
                thanhtien = thanhtien.toLocaleString(
                    undefined, // leave undefined to use the visitor's browser 
                               // locale or a string like 'en-US' to override it.
                    { maximumFractionDigits: 2 }
                  ).replace(/,/g, '.');
                  thanhtien +="đ";
                  td = $('<td></td>');
                  td.text(thanhtien); 
                  tr.append(td);
                  $('#orderForm tbody').append(tr);
            }
            $('#orderForm tbody').html()
        },
    
    });

}

$(document).ready(function() {
    const modalTitle = document.getElementById('orderModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');

    $('.open_view_form').click(function(e) {
        e.preventDefault();
        modal.classList.add('view-modal');
        modalTitle.textContent = 'Xem chi tiết đơn hàng';
        var order_id = $(this).closest('tr').find('.order_id').text();
        view(order_id);
        $('#orderForm').find('.not-edit').show();
        document.getElementById('orderForm').querySelectorAll('.edit').forEach(e => {
            e.style.setProperty('display', 'none', 'important');
        })
    });

    $('.open_edit_form').click(function(e) {
        e.preventDefault();
        
        modal.classList.remove('view-modal');
        modalTitle.textContent = 'Chỉnh sửa đơn hàng';
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');

        var order_id = $(this).closest('tr').find('.order_id').text();
        view(order_id, true);
        $('#orderForm').find('.edit').show();
        document.getElementById('orderForm').querySelectorAll('.not-edit').forEach(e => {
            e.style.setProperty('display', 'none', 'important');
        })
    });

    $('#orderForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData( $('#orderForm')[0]);
        $.ajax({
            url: '../controller/quantri/OrderController.php', // URL to handle form submission
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                const obj = JSON.parse(response);
                if(obj.success){
                    toast({
                        title: 'Thành công',
                        message: 'Cập nhật đơn hàng thành công',
                        type: 'success',
                        duration: 3000
                    });
                }
            },
        });
    });
});