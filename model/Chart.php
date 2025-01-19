<?php 
class Chart {
    function getWeeksOrderCount() {
        $sql = "WITH
                    order_by_week AS (
                        SELECT
                            YEARWEEK(ngaytao, 1) AS week_number, -- Xác định tuần
                            COUNT(*) AS order_count            -- Số lượng đơn hàng trong tuần
                        FROM donhang
                        WHERE ngaytao >= CURDATE() - INTERVAL 14 DAY -- Chỉ lấy dữ liệu trong 14 ngày gần đây
                            AND idTT = 5 -- Chỉ lấy đơn hàng đã hoàn thành
                        GROUP BY YEARWEEK(ngaytao, 1)
                    ),
                    weekly_comparison AS (
                        SELECT
                            current_week.order_count AS current_week_orders, -- Số đơn hàng tuần này
                            prev_week.order_count AS previous_week_orders    -- Số đơn hàng tuần trước
                        FROM
                            (SELECT * FROM order_by_week ORDER BY week_number DESC LIMIT 1) AS current_week
                                LEFT JOIN
                            (SELECT * FROM order_by_week ORDER BY week_number DESC LIMIT 1 OFFSET 1) AS prev_week
                            ON current_week.week_number - 1 = prev_week.week_number
                    )
                SELECT
                    current_week_orders,
                    previous_week_orders
                FROM weekly_comparison;";
        $con = new Database();
        return $con->getOne($sql);
    }

    function getWeeksRevenue() {
        $sql = "WITH
                    order_by_week AS (
                        SELECT
                            YEARWEEK(ngaytao, 1) AS week_number, -- Xác định tuần
                            SUM(tamtinh) AS order_total           -- Số lượng đơn hàng trong tuần
                        FROM donhang
                        WHERE ngaytao >= CURDATE() - INTERVAL 14 DAY -- Chỉ lấy dữ liệu trong 14 ngày gần đây
                        AND idTT = 5 -- Chỉ lấy đơn hàng đã hoàn thành
                        GROUP BY YEARWEEK(ngaytao, 1)
                    ),
                    weekly_comparison AS (
                        SELECT
                            current_week.order_total AS current_week_total, -- Số đơn hàng tuần này
                            prev_week.order_total AS previous_week_total    -- Số đơn hàng tuần trước
                        FROM
                            (SELECT * FROM order_by_week ORDER BY week_number DESC LIMIT 1) AS current_week
                                LEFT JOIN
                            (SELECT * FROM order_by_week ORDER BY week_number DESC LIMIT 1 OFFSET 1) AS prev_week
                            ON current_week.week_number - 1 = prev_week.week_number
                    )
                SELECT
                    current_week_total,
                    previous_week_total
                FROM weekly_comparison;";
        $con = new Database();
        return $con->getOne($sql);
    }

    function getWeeksQuantitySold() {
        $sql = "WITH
                    order_by_week AS (
                        SELECT
                            YEARWEEK(ngaytao, 1) AS week_number, -- Xác định tuần
                            SUM(soluong) AS quantity_total           -- Số lượng đơn hàng trong tuần
                        FROM donhang
                        JOIN
                            ctdonhang ON donhang.idDH = ctdonhang.idDH
                        WHERE ngaytao >= CURDATE() - INTERVAL 14 DAY -- Chỉ lấy dữ liệu trong 14 ngày gần đây
                        AND idTT = 5 -- Chỉ lấy đơn hàng đã hoàn thành
                        GROUP BY YEARWEEK(ngaytao, 1)
                    ),
                    weekly_comparison AS (
                        SELECT
                            current_week.quantity_total AS current_week_quantity, -- Số đơn hàng tuần này
                            prev_week.quantity_total AS previous_week_quantity    -- Số đơn hàng tuần trước
                        FROM
                            (SELECT * FROM order_by_week ORDER BY week_number DESC LIMIT 1) AS current_week
                                LEFT JOIN
                            (SELECT * FROM order_by_week ORDER BY week_number DESC LIMIT 1 OFFSET 1) AS prev_week
                            ON current_week.week_number - 1 = prev_week.week_number
                    )
                SELECT
                    current_week_quantity,
                    previous_week_quantity
                FROM weekly_comparison;";
        $con = new Database();
        return $con->getOne($sql);
    }

    function getRCP() {
        $sql = "WITH revenue AS (SELECT MONTH(ngaytao)  AS thang,
                                        SUM(dh.tamtinh) AS doanhthu
                                FROM donhang dh
                                WHERE YEAR(dh.ngaytao) = YEAR(CURDATE())
                                AND dh.idTT = 5
                                GROUP BY MONTH(ngaytao)),
                    cost AS (SELECT MONTH(ngaytao)   AS thang,
                                    SUM(pn.tongtien) AS chiphi
                            FROM phieunhap pn
                            WHERE YEAR(pn.ngaytao) = YEAR(CURDATE())
                                AND pn.trangthai = 'ht'
                            GROUP BY MONTH(ngaytao))
                SELECT r.thang,
                    COALESCE(r.doanhthu, 0) as doanhthu,
                    COALESCE(c.chiphi, 0) as chiphi,
                    COALESCE(r.doanhthu, 0) - COALESCE(c.chiphi, 0) AS loinhuan
                FROM revenue r
                        LEFT JOIN cost c ON r.thang = c.thang
                UNION
                SELECT c.thang,
                    COALESCE(r.doanhthu, 0) as doanhthu,
                    COALESCE(c.chiphi, 0) as chiphi,
                    COALESCE(r.doanhthu, 0) - COALESCE(c.chiphi, 0) AS loinhuan
                FROM revenue r
                        RIGHT JOIN cost c ON r.thang = c.thang";
        $con = new Database();
        return $con->getAll($sql);
    }

    function getTop10BestSellingBooks() {
        $sql = "SELECT  sach.idSach,
                        sach.tuasach,
                        SUM(ctdh.soluong) AS soluong
                FROM ctdonhang ctdh
                JOIN
                    sach ON ctdh.idSach = sach.idSach
                JOIN
                    donhang ON ctdh.idDH = donhang.idDH
                WHERE donhang.idTT = 5
                GROUP BY sach.idSach
                ORDER BY soluong DESC
                LIMIT 10;";
        $con = new Database();
        return $con->getAll($sql);
    }

    function getTop10LoyalCustomers() {
        $sql = "SELECT  taikhoan.idTK,
                        taikhoan.tenTK,
                        SUM(donhang.tamtinh) AS tongtien
                FROM donhang
                JOIN
                    taikhoan ON donhang.idTK = taikhoan.idTK
                WHERE donhang.idTT = 5
                GROUP BY taikhoan.idTK
                ORDER BY tongtien DESC
                LIMIT 10;";
        $con = new Database();
        return $con->getAll($sql);
    }

    function getBookQuantityByCategory() {
        $sql = "SELECT
                    MONTH(dh.ngaytao) as month,
                    SUM(ct.soluong) as quantity,
                    tl.tenTL as category
                FROM donhang dh
                JOIN
                    ctdonhang ct ON dh.idDH = ct.idDH
                JOIN
                    sach s ON ct.idSach = s.idSach
                JOIN
                    theloai tl ON s.idTL = tl.idTL
                WHERE YEAR(dh.ngaytao) = YEAR(CURDATE())
                    AND dh.idTT = 5
                GROUP BY MONTH(dh.ngaytao), tl.tenTL;";
        $con = new Database();
        return $con->getAll($sql);  
    }
}
?>