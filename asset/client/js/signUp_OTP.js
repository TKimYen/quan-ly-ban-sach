const OTP = document.querySelector("#signUp_OTP");

const errorMessageOTP = document.querySelector(".errorMessage_signUp_OTP");

let otpAttempts = 0; // Biến đếm số lần nhập sai
const maxAttempts = 5; // Giới hạn số lần nhập sai
const lockoutTime = 5; // Thời gian khóa (30 giây)
let countdownInterval; // Thời gian đếm ngược

const validateOTP = () => {
  console.log("validateOTP" + otpAttempts);
  let OTPIsValid = false;
  const regexOTP = /^\d{6}$/;

  if (OTP.value.trim() === "") {
    errorMessageOTP.innerText = "Vui lòng nhập mã OTP";
    OTPIsValid = false;
  } 
  else if (!regexOTP.test(OTP.value.trim())) {
    otpAttempts++;
    errorMessageOTP.innerText = `OTP là chuỗi 6 chữ số (VD: 123456). Còn ${
      maxAttempts - otpAttempts
    } lần nhập`;
    if (otpAttempts >= maxAttempts) {
      startLockoutCountdown();
    }
    OTPIsValid = false;
  } else {
    errorMessageOTP.innerText = "";
    OTPIsValid = true;
  }

  return OTPIsValid;
};

let isProcessing = false;

const startLockoutCountdown = () => {
  let remaningTime = lockoutTime;
  OTP.disabled = true;
  // resentOPTButton.disabled = true;
  errorMessageOTP.innerText = `Bạn đã nhập sai quá ${maxAttempts} lần. Hãy thử lại sau ${countdownInterval} giây.`;

  countdownInterval = setInterval(() => {
    remaningTime--;
    errorMessageOTP.innerText = `Bạn đã nhập sai quá ${maxAttempts} lần. Hãy thử lại sau ${remaningTime} giây.`;
    if (remaningTime <= 0) {
      clearInterval(countdownInterval);
      otpAttempts = 0;
      OTP.disabled = false;
      errorMessageOTP.innerText = "";
    }
  }, 1000);
};

function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
async function notifyAndSwitchPage() {
  toast({
    title: "Thành công",
    message: "Bạn đã đăng ký thành công",
    type: "success",
    duration: 2000,
  });
  await sleep(2000);
  window.location.href = "index.php?page=login";
}

$(document).ready(function () {
  $("#form-OTPInput").submit(function (e) {
    e.preventDefault();
    if (validateOTP()) {
      var formData = new FormData($("#form-OTPInput")[0]);
      console.log(formData);
      $.ajax({
        url: "controller/client/AuthenController.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          console.log(response);
          const obj = JSON.parse(response);
          if (obj.success) {
            alert("Bạn đã đăng ký thành công");
            notifyAndSwitchPage();
          }
          // else toast({
          //   title: 'Lỗi',
          //   message: obj.msg,
          //   type: 'error',
          //   duration: 3000
          // });
        },
        error: function () {
          alert("Có lỗi xảy ra.");
        },
        complete: function () {
          isProcessing = false;
        },
      });
    } else {
      isProcessing = false;
    }
  });
});
