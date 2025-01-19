const email = document.querySelector("#email");
const password = document.querySelector("#password");

const errorMessageEmail = document.querySelector(".errorMessage_email");
const errorMessagePassword = document.querySelector(".errorMessage_password");

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

const validateMatkhau = () => {
  let passwordIsValid = false;

  if(password.value.trim() === "") {
    errorMessagePassword.innerText = "Vui lòng nhập mật khẩu của bạn";
    passwordIsValid = false;
  } else {
    errorMessagePassword.innerText = "";
    passwordIsValid = true;
  }

  return passwordIsValid;
}

const validateFormDangNhap = () => {
  let emailIsValid = validateEmail();
  let passwordIsValid = validateMatkhau();
  
  let formIsValid = emailIsValid && passwordIsValid;
  return formIsValid;
};
$(document).ready(function(){
    // Cái này để test toast messgae thôi nha mấy ní!!!
    $('#login-form').submit(function(event) {
        event.preventDefault();
        if (validateFormDangNhap()) {
            var formData = new FormData($('#login-form')[0]);
            $.ajax({
              url: "../controller/quantri/AuthenController.php",
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
                    message: 'Đăng nhập thành công',
                    type: 'success',
                    duration: 3000
                  });
                  window.location.href='index.php';
                }else{
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
