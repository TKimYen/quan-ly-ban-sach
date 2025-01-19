function toast({
    title = 'Success',
    message = 'Tạo tài khoản thành công',
    type = 'success',
    duration = 3000
               }) {
    const main = document.getElementById('toast');
    if(main) {  //nếu có thẻ toast
        //Tạo thẻ div
        const toast = document.createElement('div');

        //icon của toast
        const icons = {
            success: 'fa-solid fa-circle-check',
            info: 'fa-solid fa-circle-info',
            warning: 'fa-solid fa-triangle-exclamation',
            error: 'fa-solid fa-bug'
        };

        //Lấy ra các icon tương ứng với type
        const icon = icons[type];
        //Thời gian hiển thị toast (s)
        const delay = (duration / 1000).toFixed(2);

        //Thêm class cho thẻ div
        toast.classList.add('toastMsg', `toast--${type}`);
        //Thêm animation cho toast
        toast.style.animation = `slideInLeft ease 0.3s, fadeOut linear 1s ${delay}s forwards`;

        //Tạo toast
        toast.innerHTML = `
                <div class="toast__icon">
                    <i class="${icon}"></i>
                </div>
                <div class="toast__body">
                    <h3 class="toast__title">${title}</h3>
                    <p class="toast__msg">${message}</p>
                </div>
                <div class="toast__close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
        `;
        main.appendChild(toast);    //Thêm toast vào thành con của main

        //Tự động xóa toast sau 3s
        const autoRemoveToast = setTimeout(function() {
            main.removeChild(toast);
        }, duration + 1000);

        //Lắng nghe sự kiện click vào nút close
        toast.onclick = function(event) {
            if(event.target.closest('.toast__close')) {
                main.removeChild(toast);
                clearTimeout(autoRemoveToast);  //ngăn chặn tự động xóa toast
            }
        };
    }
}