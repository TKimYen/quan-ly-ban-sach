$(document).ready(function () {
    $("#cancel_btn").click(function () {
    if(confirm("Bạn có chắc muốn hủy đơn hàng này không?")){
        var order_id = $('#idDH').text();
        $.ajax({
          url: "controller/client/CustomerInfoController.php",
          type: "POST",
          data: {
            'action': 'cancel_order',
            'order_id': order_id
          },
          success: function(response) {
            console.log(response);
            const obj = JSON.parse(response);
            if(obj.success) location.reload();
            else{
              toast({
                title: 'Lỗi',
                message: obj.msg,
                type: 'error',
                duration: 3000
              });
            }
          },
        });
    }
    });
});