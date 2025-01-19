const fullname = document.querySelector("#info_fullname");
const email = document.querySelector("#info_email");
const phoneNumber = document.querySelector("#info_phoneNumber");
const currentPassword = document.querySelector("#info_currentPassword");
const newPassword = document.querySelector("#info_newPassword");
const confirmNewPassword = document.querySelector("#info_confirmNewPassword");

const errorMessageFullname = document.querySelector(".errorMessage_info_fullname");
const errorMessageEmail = document.querySelector(".errorMessage_info_email");
const errorMessagePhoneNumber = document.querySelector(".errorMessage_info_phoneNumber");
const errorMessageCurrentPassword = document.querySelector(".errorMessage_info_currentPassword");
const errorMessageNewPassword = document.querySelector(".errorMessage_info_newPassword");
const errorMessageConfirmNewPassword = document.querySelector(".errorMessage_info_confirmNewPassword");

const validateFullName = () => {
    let fullnameIsValid = false;
    const regexFullName = /[a-zA-ZÀ-ỹ]+(\s[a-zA-ZÀ-ỹ]+){1,}$/;
    if (fullname.value.trim() !== "") {
        if (!regexFullName.test(fullname.value.trim())) {
            errorMessageFullname.innerText = "Họ và tên chỉ được bao gồm chữ cái (Ví dụ: Trần Đức Bo)"
            fullnameIsValid = false;
        } else {
            errorMessageFullname.innerText = "";
            fullnameIsValid = true;
        }
    } else {
        fullnameIsValid = true;
    }

    return fullnameIsValid;
}

const validateEmail = () => {
    let emailIsValid = false;
    //Định dạng email
    const regexEmail =
        /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;
    
    if(email.value.trim() !== "") {
        if(!regexEmail.test(email.value.trim())) {
            errorMessageEmail.innerText = "Vui lòng nhập đúng định dạng của email (Ví dụ: abc@example.com)";
            emailIsValid = false;
        } else {
            errorMessageEmail.innerText = "";
            emailIsValid = true;
        }
    } else {
        emailIsValid = true;
    }

    return emailIsValid;
}

const validatePhoneNumber = () => {
    let phoneNumberIsValid = false;
    //Định dạng số điện thoại
    const regexPhoneNumber = /^0[0-9]{9}$/;
    if (phoneNumber.value.trim() !== "") {
        if (!regexPhoneNumber.test(phoneNumber.value.trim())) {
            errorMessagePhoneNumber.innerText = "Vui lòng nhập đúng định dạng số điện thoại";
            phoneNumberIsValid = false;
        } else {
            errorMessagePhoneNumber.innerText = "";
            phoneNumberIsValid = true;
        }
    } else {
        phoneNumberIsValid = true;
    }

    return phoneNumberIsValid;
}

const validateCurrentPassword = () => {
    let currentPasswordIsValid = false;

    if(currentPassword.value.trim() === "") {
        errorMessageCurrentPassword.innerText = "Vui lòng nhập mật khẩu hiện tại của bạn";
        currentPasswordIsValid = false;
    } else {
        errorMessageCurrentPassword.innerText = "";
        currentPasswordIsValid = true;
    }

    return currentPasswordIsValid;
}

const validateNewPassword = () => {
    let newPasswordIsValid = false;
    //Định dạng mật khẩu
    const regexPassword = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if(newPassword.value.trim() === "") {
        errorMessageNewPassword.innerText = "Mật khẩu mới không thể để trống";
        newPasswordIsValid = false;
    } else if (!regexPassword.test(newPassword.value.trim())) {
        errorMessageNewPassword.innerText = "Mật khẩu phải có tối thiểu 8 ký tự, bao gồm ít nhất một chữ số và một kí tự in hoa (Ví dụ: examPle2)";
        newPasswordIsValid = false;
    } else if(newPassword.value.trim() !== confirmNewPassword.value.trim()) {
        errorMessageNewPassword.innerText = "Mật khẩu phải trùng khớp với xác nhận mật khẩu"
        newPasswordIsValid = false;
    }
    else {
        errorMessageNewPassword.innerText = "";
        newPasswordIsValid = true;
    }

    return newPasswordIsValid;
}

const validateConfirmNewPassword = () => {
    let confirmNewPasswordIsValid = false;
    //Định dạng mật khẩu
    if(confirmNewPassword.value.trim() === "") {
        errorMessageNewPassword.innerText = "Mật khẩu mới không thể để trống";
        newPasswordIsValid = false;
    } else if(newPassword.value.trim() !== confirmNewPassword.value.trim()) {
        errorMessageConfirmNewPassword.innerText = "Xác nhận mật khẩu phải trùng khớp với mật khẩu";
        confirmNewPasswordIsValid = false;
    } else {
        errorMessageConfirmNewPassword.innerText = "";
        confirmNewPasswordIsValid = true;
    }

    return confirmNewPasswordIsValid;
}

const validatePassword = () => {
    let currentPasswordIsValid = validateCurrentPassword();
        let newPasswordIsValid = validateNewPassword();
        let confirmNewPasswordIsValid = validateConfirmNewPassword();
        let passwordFormIsValid = currentPasswordIsValid && newPasswordIsValid && confirmNewPasswordIsValid;
        return passwordFormIsValid;
}

const validateInfoForm = () => {
    let fullnameIsValid = validateFullName();
    let emailIsValid = validateEmail();
    let phoneNumberIsValid = validatePhoneNumber();
    let infoFormIsValid = fullnameIsValid &&emailIsValid && phoneNumberIsValid;
    return infoFormIsValid;
}

$(document).ready(function () {
    $("#info_form").submit(function (e) {
        e.preventDefault();
        if (validateInfoForm()) {
            var formData = new FormData($('#info_form')[0]);
            $.ajax({
                url: "controller/client/CustomerInfoController.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if(obj.success)
                        window.location.href='index.php?page=customerInfo';
                    else
                        toast({
                          title: 'Lỗi',
                          message: obj.msg,
                          type: 'error',
                          duration: 3000
                        });
                },
            });
        }
    });

    $("#changePassword_form").submit(function (e) {
        e.preventDefault();
        if (validatePassword()) {
            var formData = new FormData($('#changePassword_form')[0]);
            $.ajax({
                url: "controller/client/CustomerInfoController.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if(obj.success){
                        alert(obj.msg);
                        window.location.href='index.php?page=changePassword';
                    }
                    else
                        toast({
                          title: 'Lỗi',
                          message: obj.msg,
                          type: 'error',
                          duration: 3000
                        });
                },
            });
        }
    });
});
  