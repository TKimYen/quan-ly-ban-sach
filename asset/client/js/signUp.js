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

const touchedFields = {
  fullname: false,
  email: false,
  phoneNumber: false,
  password: false,
  confirmPassword: false
};

fullname.addEventListener("blur", () => touchedFields.fullname = true);
email.addEventListener("blur", () => touchedFields.email = true);
phoneNumber.addEventListener("blur", () => touchedFields.phoneNumber = true);
password.addEventListener("blur", () => touchedFields.password = true);
confirmPassword.addEventListener("blur", () => touchedFields.confirmPassword = true);

const validateTenTK = () => {
  console.log("Running validateTenTK...");
  if (!touchedFields.fullname) return true; // Nếu chưa chạm vào, bỏ qua kiểm tra

  let fullnameIsValid = false;
  const regexFullName = /[a-zA-ZÀ-ỹ]+(\s[a-zA-ZÀ-ỹ]+){1,}$/;

  if(fullname.value.trim() === "") {
    errorMessageFullname.innerText = "Họ và tên không được để trống";
  } else if (!regexFullName.test(fullname.value.trim())) {
    errorMessageFullname.innerText = "Họ và tên chỉ được bao gồm chữ cái (Ví dụ: Trần Đức Bo)";
  } else {
    errorMessageFullname.innerText = "";
    fullnameIsValid = true;
  }

  return fullnameIsValid;
};

const validateEmail = () => {
  if (!touchedFields.email) return true;

  let emailIsValid = false;
  const regexEmail =
    /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;

  if(email.value.trim() === "") {
    errorMessageEmail.innerText = "Email không được để trống";
  } else if(!regexEmail.test(email.value.trim())) {
    errorMessageEmail.innerText = "Vui lòng nhập đúng định dạng email (Ví dụ: abc@example.com)";
  } else {
    errorMessageEmail.innerText = "";
    emailIsValid = true;
  }

  return emailIsValid;
};

const validateDienthoai = () => {
  if (!touchedFields.phoneNumber) return true;

  let phoneNumberIsValid = false;
  const regexPhoneNumber = /^0[0-9]{9}$/;

  if(phoneNumber.value.trim() === "") {
    errorMessagePhoneNumber.innerText = "Số điện thoại không được để trống";
  } else if (!regexPhoneNumber.test(phoneNumber.value.trim())) {
    errorMessagePhoneNumber.innerText = "Vui lòng nhập đúng định dạng số điện thoại";
  } else {
    errorMessagePhoneNumber.innerText = "";
    phoneNumberIsValid = true;
  }

  return phoneNumberIsValid;
};

const validateMatkhau = () => {
  if (!touchedFields.password) return true;

  let passwordIsValid = false;
  const regexPassword = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

  if(password.value.trim() === "") {
    errorMessagePassword.innerText = "Mật khẩu không được để trống";
  } else if (!regexPassword.test(password.value.trim())) {
    errorMessagePassword.innerText = "Mật khẩu phải có tối thiểu 8 ký tự, gồm ít nhất một chữ số và một chữ in hoa";
  } else if(password.value.trim() !== confirmPassword.value.trim() && touchedFields.confirmPassword) {
    errorMessagePassword.innerText = "Mật khẩu phải trùng khớp với xác nhận mật khẩu";
  } else {
    errorMessagePassword.innerText = "";
    passwordIsValid = true;
  }

  return passwordIsValid;
};

const validateXacNhanMatkhau = () => {
  if (!touchedFields.confirmPassword) return true;

  let confirmPasswordIsValid = false;

  if(confirmPassword.value.trim() === "") {
    errorMessageConfirmPassword.innerText = "Xác nhận mật khẩu không được để trống";
  } else if(password.value.trim() !== confirmPassword.value.trim()) {
    errorMessageConfirmPassword.innerText = "Xác nhận mật khẩu phải trùng khớp với mật khẩu";
  } else {
    errorMessageConfirmPassword.innerText = "";
    confirmPasswordIsValid = true;
  }

  return confirmPasswordIsValid;
};


fullname.addEventListener("input", validateTenTK);
fullname.addEventListener("blur", validateTenTK);

email.addEventListener("input", validateEmail);
email.addEventListener("blur", validateEmail);

phoneNumber.addEventListener("input", validateDienthoai);
phoneNumber.addEventListener("blur", validateDienthoai);

password.addEventListener("input", validateMatkhau);
password.addEventListener("blur", validateMatkhau);

confirmPassword.addEventListener("input", validateXacNhanMatkhau);
confirmPassword.addEventListener("blur", validateXacNhanMatkhau);


$(document).ready(function () {
  console.log("hi");
  $("#signUp_form").submit(function (e) {
    console.log("submit");
    e.preventDefault();
  
    let formIsValid = validateTenTK() &&
                      validateEmail() &&
                      validateDienthoai() &&
                      validateMatkhau() &&
                      validateXacNhanMatkhau();
  
    if (!formIsValid) {
      return; // Dừng lại nếu form không hợp lệ
    }
  
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
        if(obj.success) {
          window.location.href = 'index.php?page=signUp_OTP';
        } else {
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
  });
});