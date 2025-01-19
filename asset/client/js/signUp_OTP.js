const OTP = document.querySelector("#signUp_OTP");

const errorMessageOTP = document.querySelector(".errorMessage_signUp_OTP");

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

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
async function notifyAndSwitchPage() {
  toast({
    title: 'Thành công',
    message: 'Bạn đã đăng ký thành công',
    type: 'success',
    duration: 2000
  });
  await sleep(2000);
  window.location.href='index.php?page=login';
}

$(document).ready(function () {
    $("#form-OTPInput").submit(function (e) {
      e.preventDefault();
      if (validateOTP()) {
        var formData = new FormData($('#form-OTPInput')[0]);
        console.log(formData);
        $.ajax({
          url: "controller/client/AuthenController.php",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            console.log(response);
            const obj = JSON.parse(response);
            if(obj.success){
              alert("Bạn đã đăng ký thành công");
              notifyAndSwitchPage();
            }
            else toast({
              title: 'Lỗi',
              message: obj.msg,
              type: 'error',
              duration: 3000
            });
          },
          error: function() {
            alert("Có lỗi xảy ra.");
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