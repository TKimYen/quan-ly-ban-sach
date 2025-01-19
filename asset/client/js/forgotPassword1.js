const email = document.querySelector("#forgotPassword_email");

const errorMessageEmail = document.querySelector(".errorMessage_forgotPassword_email");

const validateEmail = () => {
    let emailIsValid = false;
    const regexEmail =
      /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;
  
    if(email.value.trim() === "") {
      errorMessageEmail.innerText = "Vui lòng nhập email của bạn";
      emailIsValid = false;
    } else if(!regexEmail.test(email.value.trim())) {
      errorMessageEmail.innerText = "Vui lòng nhập đúng định dạng của email (Ví dụ: abc@example.com)";
      emailIsValid = false;
    } else {
      errorMessageEmail.innerText = "";
      emailIsValid = true;
    }
  
    return emailIsValid;
}

let isProcessing = false;

$(document).ready(function () {
    $("#form-forgotPassword").submit(function (e) {
      e.preventDefault();

      if (isProcessing) return;
    isProcessing = true;

      if (validateEmail()) {
        var formData = new FormData($('#form-forgotPassword')[0]);
        console.log(formData);
        toast({
          title: 'Đang xử lý',
          message: 'Mã xác nhận đang được gửi đến email của bạn',
          type: 'info',
          duration: 3000
        });
        $.ajax({  
          url: "controller/client/ForgotPasswordController.php",
          type: "POST",
          data: formData,
          processData: false, 
          contentType: false,
          success: function(response) {
            console.log(response);
            const obj = JSON.parse(response);
            if(obj.success) window.location.href='index.php?page=show_OTPInputForm';
            else 
            toast({
              title: 'Lỗi',
              message: obj.msg,
              type: 'error',
              duration: 3000
            });
          },
          error: function() {
            toast({
              title: 'Lỗi',
              message: 'Có lỗi xảy ra',
              type: 'error',
              duration: 3000
            });
          },
          complete: function() {
              isProcessing = false;
          }
        });
      } else {
        isProcessing = false;
      } 
    });
});