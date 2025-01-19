$(document).ready(function() {
    function createMonthSelectBox() {
        let selectBox = '<select name="month" id="month" class="form-select">';
        let month = new Date().getMonth() + 1;
        let year = new Date().getFullYear();
        for (let i = 1; i <= month; i++) {
            let isSelected = i == month ? 'selected' : '';
            for (let j = 2024; j <= year; j++) {
                selectBox += `<option value="${j}-${i}" ${isSelected}>Tháng ${i}/${j}</option>`;
            }
        }
        selectBox += '</select>';
        return selectBox;
    }

    function createYearSelectBox() {
        let selectBox = '<select name="year" id="year" class="form-select">';
        let year = new Date().getFullYear();
        for (let i = 2024; i <= year; i++) {
            selectBox += `<option value="${i}">${i}</option>`;
        }
        selectBox += '</select>';
        return selectBox;
    }

    function formatPrice(price) {
        return price.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'})
    }

    function dateFormater(string) {
        let date = new Date(string);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        return formattedDate;
    }

    function reportTableView(time, type, data) {
        let template = `
        <table class="table table-bordered border-dark tk-table text-center">
            <tr class="table-title bg-success text-white">
                <th>${time == "month" ? "Tuần thứ" : "Tháng"}</th>
                <th>Từ ngày</th>
                <th>Đến ngày</th>
                <th>
                    Số đơn
                    <br>
                    <span class="fst-italic">(đơn)</span>
                </th>
                <th>
                    Số sản phẩm
                    <br>
                    <span class="fst-italic">(cuốn)</span>
                </th>
                <th>${type == "income" ? "Doanh thu" : "Chi"}</th>
            </tr>
        `;
        for (let key in data.time) {
            let length = data.time[key].length;
            template += `
            <tr class="table-data">
                <td>${key}</td>
                <td>${dateFormater(data.time[key][0])}</td>
                <td>${dateFormater(data.time[key][length - 1])}</td>
                <td>${data.count[key]}</td>
                <td>${data.productCount[key]}</td>
                <td class="text-end">${formatPrice(data.total[key])}</td>
            </tr>
            `;
        }

        template += `
        <tr class="table-data sum-row">
                <td colspan="3" class="bg-success text-white fw-bold">TỔNG</td>
                <td>${data.sumCount}</td>
                <td>${data.sumProductCount}</td>
                <td class="text-end">${formatPrice(data.sumTotal)}</td>
            </tr>
        </table>
        <p class="numberInWords mb-5 text-end fst-italic">
            <span class="numberInWords-title fw-bold">Viết bằng chữ: </span>
            <span class="numberInWords-content">${data.numberToWords}</span>
        </p>
        <div class="d-flex justify-content-center">
            <button class="btn btn-success tk-button" id="in-tk">
                <i class="fa-regular fa-print me-2"></i>
                In thống kê
            </button>
        </div>
        `;
       $('#tk-container').html(template);
    }

    function reportProfitTableView(time, data) {
        let template = `
        <table class="table table-bordered border-dark tk-table text-center">
            <tr class="table-title bg-success text-white">
                <th>${time == "month" ? "Tuần thứ" : "Tháng"}</th>
                <th>Từ ngày</th>
                <th>Đến ngày</th>
                <th>Doanh thu</th>
                <th>Chi</th>
                <th>Lợi nhuận</th>
            </tr>
        `;
        for (let key in data.time) {
            let length = data.time[key].length;
            template += `
            <tr class="table-data">
                <td>${key}</td>
                <td>${dateFormater(data.time[key][0])}</td>
                <td>${dateFormater(data.time[key][length - 1])}</td>
                <td class="text-end">${formatPrice(data.orderTotal[key])}</td>
                <td class="text-end">${formatPrice(data.grnTotal[key])}</td>
                <td class="text-end">${formatPrice(data.profit[key])}</td>
            </tr>
            `;
        }
        template += `
        <tr class="table-data sum-row">
                <td colspan="3" class="bg-success text-white fw-bold">TỔNG</td>
                <td class="text-end">${formatPrice(data.sumOrderTotal)}</td>
                <td class="text-end">${formatPrice(data.sumGRNTotal)}</td>
                <td class="text-end">${formatPrice(data.sumProfit)}</td>
            </tr>
        </table>
        <p class="numberInWords mb-5 text-end fst-italic">
            <span class="numberInWords-title fw-bold">Viết bằng chữ: </span>
            <span class="numberInWords-content">${data.numberToWords}</span>
        </p>
        <div class="d-flex justify-content-center">
            <button class="btn btn-success tk-button" id="in-tk">
                <i class="fa-regular fa-print me-2"></i>
                In thống kê
            </button>
        </div>
        `;
         $('#tk-container').html(template);
    }

    function renderReport(typeOfReport, time) {
        let url = typeOfReport == "income" ? "Income" : (typeOfReport == "cost" ? "Cost" : "Profit");
        console.log(url);
        let selectTime = "";
        if (time == "month") {
            selectTime = $('#month').val();
        } else {
            selectTime = $('#year').val();
        }
        
        $.ajax({
            url: `../controller/quantri/${url}Controller.php`,
            type: "POST",
            data: {
                'time': time,
                'selectTime': selectTime,
                'action': 'show'
            },
            success: function(data) {
                toast({
                    title: "Thành công",
                    message: "Lấy dữ liệu thành công",
                    type: data.status,
                    duration: 1000
                });
                // Hàm render
                if (typeOfReport == "profit") {
                    reportProfitTableView(time, data);
                } else {
                    console.log(data);
                    reportTableView(time, typeOfReport, data);
                }
            },
            error: function(log) {
                toast({
                    title: "Lỗi",
                    message: log.message,
                    type: log.status
                });
            }
        });
    }

    /* Render report table automatically when loading page */
    $('#select-time').html(createMonthSelectBox());

    renderReport(
        $('#type-of-report').val(),
        $('#time').val()
    );

    $('#time').on('change', function() {
        let typeOfReport = $('#type-of-report').val();
        let time = $(this).val();
        if (time == "month") {
            $('#select-time').html(createMonthSelectBox());
        } else if (time == "year") {
            $('#select-time').html(createYearSelectBox());
        }
        renderReport(typeOfReport, time);
    });

    $('#select-time').on('change', function() {
        let typeOfReport = $('#type-of-report').val();
        let time = $("#time").val();
        renderReport(typeOfReport, time);   
    })

    $('#tk-container').on('click', '#in-tk', function() {
        let time = $('#time').val();
        let selectTime = "";
        if (time == "month") {
            selectTimeUrl = "month";
            selectTime = $('#month').val();
        } else {
            selectTimeUrl = "year";
            selectTime = $('#year').val();
        }
        let typeOfReport = $('#type-of-report').val();
        window.open("../controller/quantri/PrintReports.php?report=" + typeOfReport + "&time=" + time + "&" + selectTimeUrl + "=" + selectTime);
    });
});