<main class="container pt-5">
    <!-- Page title -->
    <div class="row">
        <h1 class="page-title">THỐNG KÊ</h1>
    </div>
    <!-- ... -->
    <!-- Cards group -->
    <div class="row">
        <?php
        $weeksRevenue = ChartController::getWeeksRevenue();
        $weeksOrderCount = ChartController::getWeeksOrderCount();
        $weeksQuantitySold = ChartController::getWeeksQuantitySold();
        ?>
        <div class="col-lg-4 stretch-card grid-margin">
            <div class="card box-shadow-card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="../asset/quantri/img/circle.svg" class="card-img-absolute" alt="circle-image">
                    <h4 class="card-text-custom font-weight-normal mb-3">Doanh Thu Trong Tuần
                        <i class="card-icon fa-regular fa-chart-line"></i>
                    </h4>
                    <h2 class="card-text-custom mb-5"><?= number_format($weeksRevenue['current_week_total'], 0, ',', '.') ?> đ</h2>
                    <?php
                    if ($weeksRevenue['compare'] < 0) {
                        echo '<h6 class="card-text-custom card-text">Giảm ' . abs($weeksRevenue['compare']) . '% so với tuần trước</h6>';
                    } else if ($weeksRevenue['compare'] >= 0) {
                        echo '<h6 class="card-text-custom card-text">Tăng ' . $weeksRevenue['compare'] . '% so với tuần trước</h6>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 stretch-card grid-margin">
            <div class="card box-shadow-card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="../asset/quantri/img/circle.svg" class="card-img-absolute" alt="circle-image">
                    <h4 class="card-text-custom font-weight-normal mb-3">Đơn Hàng Trong Tuần
                        <i class="card-icon fa-regular fa-box-dollar"></i>
                    </h4>
                    <h2 class="card-text-custom mb-5"><?= number_format($weeksOrderCount['current_week_orders'], 0, ',', '.') ?></h2>
                    <?php
                    if ($weeksOrderCount['compare'] < 0) {
                        echo '<h6 class="card-text-custom card-text">Giảm ' . abs($weeksOrderCount['compare']) . '% so với tuần trước</h6>';
                    } else if ($weeksOrderCount['compare'] >= 0) {
                        echo '<h6 class="card-text-custom card-text">Tăng ' . $weeksOrderCount['compare'] . '% so với tuần trước</h6>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 stretch-card grid-margin">
            <div class="card box-shadow-card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="../asset/quantri/img/circle.svg" class="card-img-absolute" alt="circle-image">
                    <h4 class="card-text-custom font-weight-normal mb-3">Sản Phẩm Bán Trong Tuần
                        <i class="card-icon fa-regular fa-book-heart"></i>
                    </h4>
                    <h2 class="card-text-custom mb-5"><?= number_format($weeksQuantitySold['current_week_quantity'], 0, ',', '.') ?></h2>
                    <?php
                    if ($weeksQuantitySold['compare'] < 0) {
                        echo '<h6 class="card-text-custom card-text">Giảm ' . abs($weeksQuantitySold['compare']) . '% so với tuần trước</h6>';
                    } else if ($weeksQuantitySold['compare'] >= 0) {
                        echo '<h6 class="card-text-custom card-text">Tăng ' . $weeksQuantitySold['compare'] . '% so với tuần trước</h6>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- ... -->
    <!-- Chart group -->
    <div class="row mb-2">
        <div class="col">
            <div class="card card-shadow chart-group">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 fw-bolder fs-4 text-success">Thống kê theo:</div>
                        <div class="col-md-6">
                            <select class="form-select" id="chart-select">
                                <option value="1" selected>Doanh thu - Chi phí - Lợi nhuận trong năm</option>
                                <option value="2">Top 10 sách bán chạy nhất</option>
                                <option value="3">Top 10 khách hàng chi tiêu nhiều nhất</option>
                                <option value="4">Số lượng sách bán được theo thể loại trong năm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body chart-container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- ... -->
</main>

<!-- Link JS -->
<script src="../asset/quantri/js/Chart.js"></script>
<!-- Link JS -->