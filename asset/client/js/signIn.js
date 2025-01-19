const email = document.querySelector("#signIn_email");
const password = document.querySelector("#signIn_password");

const errorMessageEmail = document.querySelector(".errorMessage_signIn_email");
const errorMessagePassword = document.querySelector(".errorMessage_signIn_password");

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

$(document).ready(function () {
    $("#signIn_form").submit(function (e) {
      e.preventDefault();
      if (validateFormDangNhap()) {
        var formData = new FormData($('#signIn_form')[0]);
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
              window.location.href='index.php?page=home';
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