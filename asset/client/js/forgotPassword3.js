const password = document.querySelector("#passwordRecovery_password");
const confirmPassword = document.querySelector("#passwordRecovery_confirmPassword");

const errorMessagePassword = document.querySelector(".errorMessage_forgotPassword_password");
const errorMessageConfirmPassword = document.querySelector(".errorMessage_forgotPassword_confirmPassword");

const validatePassword = () => {
    let passwordIsValid = false;
    //Định dạng mật khẩu
    const regexPassword = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
  
    if(password.value.trim() === "") {
      errorMessagePassword.innerText = "Mật khẩu không được để trống";
      passwordIsValid = false;
    } else if (!regexPassword.test(password.value.trim())) {
      errorMessagePassword.innerText = "Mật khẩu phải có tối thiểu 8 ký tự, bao gồm ít nhất một chữ số và một kí tự in hoa (Ví dụ: examPle2)";
      passwordIsValid = false;
    } else if(password.value.trim() !== confirmPassword.value.trim()) {
      errorMessagePassword.innerText = "Mật khẩu phải trùng khớp với xác nhận mật khẩu"
      passwordIsValid = false;
    }
    else {
      errorMessagePassword.innerText = "";
      passwordIsValid = true;
    }
  
    return passwordIsValid;
}
  
const validateConfirmPassword = () => {
    let confirmPasswordIsValid = false;

    if(confirmPassword.value.trim() === "") {
        errorMessageConfirmPassword.innerText = "Xác nhận mật khẩu không được để trống";
        confirmPasswordIsValid = false;
    } else if(password.value.trim() !== confirmPassword.value.trim()) {
        errorMessageConfirmPassword.innerText = "Xác nhận mật khẩu phải trùng khớp với mật khẩu";
        confirmPasswordIsValid = false;
        } else {
        errorMessageConfirmPassword.innerText = "";
        confirmPasswordIsValid = true;
    }

    return confirmPasswordIsValid;
}

const validateFormForgotPassword = () => {
    let passwordIsValid = validatePassword();
    let confirmPasswordIsValid = validateConfirmPassword();

    let formIsValid = passwordIsValid && confirmPasswordIsValid;
    return formIsValid;
}

let isProcessing = false;

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
async function notifyAndSwitchPage() {
  toast({
    title: 'Thành công',
    message: 'Bạn đã thay đổi mật khẩu thành công',
    type: 'success',
    duration: 2000
  });
  await sleep(2000);
  window.location.href='index.php?page=login';
}

$(document).ready(function () {
    $("#form-passwordRecovery").submit(function (e) {
      e.preventDefault();
      if (validateFormForgotPassword()) {
        var formData = new FormData($('#form-passwordRecovery')[0]);
        
        $.ajax({
          url: "controller/client/ForgotPasswordController.php",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            console.log(response);
            const obj = JSON.parse(response);
            notifyAndSwitchPage();
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