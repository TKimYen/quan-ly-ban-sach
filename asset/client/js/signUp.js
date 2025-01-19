const fullname = document.querySelector("#signUp_fullname");
const email = document.querySelector("#signUp_email");
const phoneNumber = document.querySelector("#signUp_phoneNumber");
const password = document.querySelector("#signUp_password");
const confirmPassword = document.querySelector("#signUp_confirmPassword");

const errorMessageFullname = document.querySelector(".errorMessage_signUp_fullname");
const errorMessageEmail = document.querySelector(".errorMessage_signUp_email");
const errorMessagePhoneNumber = document.querySelector(".errorMessage_signUp_phoneNumber");
const errorMessagePassword = document.querySelector(".errorMessage_signUp_password");
const errorMessageConfirmPassword = document.querySelector(".errorMessage_signUp_confirmPassword");

const validateTenTK = () => {
  let fullnameIsValid = false;
  const regexFullName = /[a-zA-ZÀ-ỹ]+(\s[a-zA-ZÀ-ỹ]+){1,}$/;

  if(fullname.value.trim() === "") {
    errorMessageFullname.innerText = "Họ và tên không được để trống";
    fullnameIsValid = false;
  } else if (!regexFullName.test(fullname.value.trim())) {
    errorMessageFullname.innerText = "Họ và tên chỉ được bao gồm chữ cái (Ví dụ: Trần Đức Bo)"
    fullnameIsValid = false;
  } else {
    errorMessageFullname.innerText = "";
    fullnameIsValid = true;
  }

  return fullnameIsValid;
}

const validateEmail = () => {
  let emailIsValid = false;
  //Định dạng email
  const regexEmail =
    /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;

  if(email.value.trim() === "") {
    errorMessageEmail.innerText = "Email không được để trống";
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

const validateDienthoai = () => {
  let phoneNumberIsValid = false;
  //Định dạng số điện thoại
  const regexPhoneNumber = /^0[0-9]{9}$/;

  if(phoneNumber.value.trim() === "") {
    errorMessagePhoneNumber.innerText = "Số điện thoại không được để trống";
    phoneNumberIsValid = false;
  } else if (!regexPhoneNumber.test(phoneNumber.value.trim())) {
    errorMessagePhoneNumber.innerText = "Vui lòng nhập đúng định dạng số điện thoại";
    phoneNumberIsValid = false;
  } else {
    errorMessagePhoneNumber.innerText = "";
    phoneNumberIsValid = true;
  }

  return phoneNumberIsValid;
}

const validateMatkhau = () => {
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

const validateXacNhanMatkhau = () => {
  let confirmPasswordIsValid = false;
  //Định dạng mật khẩu

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

const validateFormDangKy = () => {
  let fullnameIsValid = validateTenTK()
  let emailIsValid = validateEmail();
  let phoneNumberIsValid = validateDienthoai();
  let passwordIsValid = validateMatkhau()
  let confirmPasswordIsValid = validateXacNhanMatkhau()
  let formIsValid = fullnameIsValid &&
                              emailIsValid &&
                              phoneNumberIsValid &&
                              passwordIsValid &&
                              confirmPasswordIsValid;
  return formIsValid;
}

$(document).ready(function () {
  $("#signUp_form").submit(function (e) {
    e.preventDefault();
    if (validateFormDangKy()) {
      var formData = new FormData($('#signUp_form')[0]);
      toast({
        title: 'Đang xử lý',
        message: 'Mã xác nhận đang được gửi đến email của bạn',
        type: 'info',
        duration: 3000
      });
      $.ajax({
        url: "controller/client/AuthenController.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          console.log(response);
          const obj = JSON.parse(response);
          if(obj.success) window.location.href='index.php?page=signUp_OTP';
          else{
            toast({
              title: 'Lỗi',
              message: obj.msg,
              type: 'error',
              duration: 3000
            });
            $('#signUp_email').focus();
          }
        },
      });
    }
  });
});