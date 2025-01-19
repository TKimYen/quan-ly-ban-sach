<div class="col-3">
    <div class="personal-menu">
        <div class="username">
            <i class="fa-thin fa-circle-user"></i>
            <h5><?=$_SESSION['user']['tenTK']?></h5>
        </div>
        <div class="menu">
            <div class="menu-item info-personal">
                <a href="?page=customerInfo" class="nav-link">
                    <i class="fa-thin fa-user"></i>
                    <span>Thông tin cá nhân</span>
                </a>
            </div>
            <div class="menu-item order-history">
                <a href="?page=orderHistory" class="nav-link">
                    <i class="fa-thin fa-newspaper"></i>
                    <span>Lịch sử đơn hàng</span>
                </a>
            </div>
            <div class="menu-item change-password">
                <a href="?page=changePassword" class="nav-link">
                    <i class="fa-thin fa-key"></i>
                    <span>Thay đổi mật khẩu</span>
                </a>
            </div>
        </div>
    </div>
</div>