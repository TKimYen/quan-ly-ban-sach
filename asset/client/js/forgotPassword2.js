const OTP = document.querySelector("#forgotPassword_OTP");

const errorMessageOTP = document.querySelector(".errorMessage_forgotPassword_OTP");

const validateOTP = () => {
    let OTPIsValid = false;
    const regexOTP = /^\d{6}$/;
  
    if(OTP.value.trim() === "") {
      errorMessageOTP.innerText = "Vui lòng nhập mã OTP";
      OTPIsValid = false;
    } else if(!regexOTP.test(OTP.value.trim())) {
      errorMessageOTP.innerText = "OTP là chuỗi 6 chữ số (VD: 123456)";
      OTPIsValid = false;
    } else {
      errorMessageOTP.innerText = "";
      OTPIsValid = true;
    }
  
    return OTPIsValid;
}

let isProcessing = false;

$(document).ready(function () {
    $("#form-OTPInput").submit(function (e) {
      e.preventDefault();
      if (validateOTP()) {
        var formData = new FormData($('#form-OTPInput')[0]);
        console.log(formData);
        $.ajax({
          url: "controller/client/ForgotPasswordController.php",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            console.log(response);
            const obj = JSON.parse(response);
            if(obj.success) window.location.href='index.php?page=show_changePasswordForm';
            else toast({
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