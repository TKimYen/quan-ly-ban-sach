const accountModal = document.getElementById('accountModal');
accountModal.addEventListener('hidden.bs.modal', function() {
    document.getElementById('accountForm').reset();
    let textMessage = document.querySelectorAll('.text-message');
    textMessage.forEach(element => {
        element.textContent = '';
    });
    location.reload();
});
    // ========================== Validate Form ==============================

// Lấy giá trị của DOM
const fullname = document.querySelector("#username");
const email = document.querySelector("#usermail");
const phoneNumber = document.querySelector("#userphone");
const password = document.querySelector("#password");

const errorMessageFullname = document.querySelector(".user-name-msg");
const errorMessageEmail = document.querySelector(".user-email-msg");
const errorMessagePhoneNumber = document.querySelector(".user-phone-msg");
const errorMessagePassword = document.querySelector(".user-password-msg");


//Các function ValidateForm
const validateFullname = () => {
    let fullnameIsValid = false;
    const regexFullName = /[a-zA-ZÀ-ỹ]+(\s[a-zA-ZÀ-ỹ]+){1,}$/;
  
    if(fullname.value.trim() === "") {
      errorMessageFullname.innerText = "Họ và tên không được để trống";
      fullnameIsValid = false;
    } else if (!regexFullName.test(fullname.value.trim())) {
      errorMessageFullname.innerText = "Họ và tên chỉ được bao gồm chữ cái và ký tự khoảng trắng (Ví dụ: Trần Đức Duy)"
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
      // /^(([A-Za-z0-9]+((\.|\-|\_|\+)?[A-Za-z0-9]?)*[A-Za-z0-9]+)|[A-Za-z0-9]+)@(([A-Za-z0-9]+)+((\.|\-|\_)?([A-Za-z0-9]+)+)*)+\.([A-Za-z]{2,})+$/;
      /^(?=.{1,255}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
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
  
  const validatePhoneNumber = () => {
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
  
  const validatePassword = () => {
    let passwordIsValid = false;
    //Định dạng mật khẩu
    const regexPassword = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;
  
    if(password.value.trim() === "") {
      errorMessagePassword.innerText = "Mật khẩu không được để trống";
      passwordIsValid = false;
    } else if (!regexPassword.test(password.value.trim())) {
      errorMessagePassword.innerText = "Mật khẩu phải có tối thiểu 8 ký tự, bao gồm ít nhất một chữ số, một kí tự in thường và một kí tự in hoa (Ví dụ: examPle2)";
      passwordIsValid = false;
    } else {
      errorMessagePassword.innerText = "";
      passwordIsValid = true;
    }
  
    return passwordIsValid;
  }
  
  
  function validateFormAccount(isEdit){
    let fullnameIsValid = validateFullname();
    let emailIsValid = validateEmail();
    let phoneNumberIsValid = validatePhoneNumber();
    let passwordIsValid;
    if(isEdit) passwordIsValid = true;
    else passwordIsValid = validatePassword();

    let formIsValid = fullnameIsValid &&
                                emailIsValid &&
                                phoneNumberIsValid &&
                                passwordIsValid;
    return formIsValid;
  }

// ======================== End Validate Form =============================

$(document).ready(function() {
    const modalTitle = document.getElementById('accountModalLabel');
    const modalSaveBtn = document.getElementById('saveModalBtn');
    var submit_btn = document.getElementById('submit_btn');
    // open add form
    $('.open_add_form').click(function() {
        modalTitle.textContent = 'Thêm tài khoản';
        modalSaveBtn.textContent = 'Thêm tài khoản';
        $("#password").prop('disabled', false);
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_add');
        $.ajax({
            url: '../controller/quantri/AccountController.php',
            type: 'POST',
            data: {
                'action': 'open_add_form',
            },
            success: function(response) {
                console.log(response);
                const data = JSON.parse(response);
                const selectElement = $('#role-select');

                selectElement.empty();

                data.forEach(item => {
                    selectElement.append(new Option(item.tenNQ, item.idNQ));
                });
                document.getElementById('accountForm').querySelector('.edit').style.display = 'none';
            },
        });
    });
   // open edit form
   $('.open_edit_form').click(function(e) {
        e.preventDefault();
        var account_id = $(this).closest('tr').find('.account_id').text();
        modalTitle.textContent = 'Chỉnh sửa tài khoản';
        modalSaveBtn.textContent = 'Lưu thay đổi';
        $("#password").prop('disabled', true);
        submit_btn.setAttribute('name', 'action');
        submit_btn.setAttribute('value', 'submit_btn_update');
        $.ajax({
            url: '../controller/quantri/AccountController.php',
            type: 'POST',
            data: {
                'action': 'edit_data',
                'account_id': account_id,
            },
            success: function(response) {
                console.log(response);
                const obj = JSON.parse(response);
                const account = obj.account;
                const role = obj.role;
                $('#account_id').val(account.idTK);
                $('#username').val(account.tenTK);
                $('#usermail').val(account.email);
                $('#userphone').val(account.dienthoai);
                
                if(parseInt(account.trangthai)){
                    $('#status').prop('checked', true);
                    $('#switch-label').text('Đang hoạt động');
                } 
                else {
                    $('#status').prop('checked', false);
                    $('#switch-label').text('Bị khóa');
                }
                // phan quyen
                const selectElement = $('#role-select');

                selectElement.empty();

                role.forEach(item => {
                    selectElement.append(new Option(item.tenNQ, item.idNQ));
                });

                selectElement.val(account.idNQ);
                document.getElementById('accountForm').querySelector('.edit').style.display = 'flex';
            },
        });
    });
    
    $('#accountForm').submit(function(event) {
        event.preventDefault();
        let isEdit = false;
        if(submit_btn.value == 'submit_btn_update') isEdit=true;

        // Nếu không có lỗi, gửi dữ liệu
        if (validateFormAccount(isEdit)) {
            var formData = new FormData($('#accountForm')[0]);
            
            $.ajax({
                url: '../controller/quantri/AccountController.php', 
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    const obj = JSON.parse(response);
                    if (obj.success) {
                        if (obj.btn === 'add') {
                            toast({
                                title: 'Thành công',
                                message: 'Thêm tài khoản thành công',
                                type: 'success',
                                duration: 3000
                            });
                        } else {
                            toast({
                                title: 'Thành công',
                                message: 'Cập nhật tài khoản thành công',
                                type: 'success',
                                duration: 3000
                            });
                        }
                    } 
                    else {
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