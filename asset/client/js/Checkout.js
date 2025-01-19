$(document).ready(function () {
    $('#payment-method-form').on('change', function () {
        if ($('#banking').is(':checked')) {
            $('#qr-code-container').show()
        } else {
            $('#qr-code-container').hide()
        }
    })

    if (!$('#banking').is(':checked')) {
        $('#qr-code-container').hide()
    }

    // Generate District Selectbox
    $('#province-selectbox').on('change', function () {
        let province_id = parseInt($(this).val());
        if (province_id !== 0) {
            $.ajax({
                url: 'controller/client/CheckoutController.php',
                type: 'get',
                data: {
                    page: 'get-districts',
                    province_id: province_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        let options = '<option value="0" selected>Chọn Quận/Huyện</option>';
                        response.data.forEach(district => {
                            options += `<option value="${district.idQuan}">${district.tenQuan}</option>`;
                        });
                        $('#district-selectbox').html(options);
                        // $('#ward-selectbox').html('<option value="0" selected>Chọn Phường/Xã</option>');
                    } else {
                        toast({
                            title: 'Thất bại',
                            message: response.message,
                            type: response.status
                        });
                    }
                }
            });
        } else {
            $('#district-selectbox').html('<option value="0" selected>Chọn Quận/Huyện</option>');
            $('#ward-selectbox').html('<option value="0" selected>Chọn Phường/Xã</option>');
        }
    });

    // Generate Ward Selectbox
    $('#district-selectbox').on('change', function () {
        let district_id = parseInt($(this).val());
        if (district_id !== 0) {
            $.ajax({
                url: 'controller/client/CheckoutController.php',
                type: 'get',
                data: {
                    page: 'get-wards',
                    district_id: district_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        let options = '<option value="0" selected>Chọn Phường/Xã</option>';
                        response.data.forEach(ward => {
                            options += `<option value="${ward.idXa}">${ward.tenXa}</option>`;
                        });
                        $('#ward-selectbox').html(options);
                    } else {
                        toast({
                            title: 'Thất bại',
                            message: response.message,
                            type: response.status
                        });
                    }
                }
            });
        } else {
            $('#ward-selectbox').html('<option value="0" selected>Chọn Phường/Xã</option>');
        }
    });

    // Get total weight of cart
    async function getCartTotalWeight() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'controller/client/CheckoutController.php',
                type: 'get',
                data: {
                    page: 'get-cart-total-weight'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status == 'success') {
                        resolve(parseFloat(response.data / 1000)); // Trả về giá trị trọng lượng (kg)
                    } else {
                        toast({
                            title: 'Thất bại',
                            message: response.message,
                            type: response.status
                        });
                        reject('Không thể lấy trọng lượng'); // Báo lỗi
                    }
                },
                error: function () {
                    reject('AJAX Error');
                }
            });
        });
    }


    // Checkout Address submit form event handler
    $('#checkout-address-form').on('submit', async function (e) {
        e.preventDefault();
        let sonharegex = /^(\d+(\/\d+)?(\/\d*[A-Z]?\d*)?|[A-Z]\d+(\s[A-Z]\d+)?)\s[\p{L}]+([\s\p{L}\d\.,\-]+)*$/u;
        if ($('#province-selectbox').val() == 0 || $('#district-selectbox').val() == 0 || $('#ward-selectbox').val() == 0 || $('#address-input').val() == '') {
            toast({
                title: 'Thất bại',
                message: 'Vui lòng điền đầy đủ thông tin',
                type: 'error',
                duration: 5000
            });
            return;
        }
        else if(!sonharegex.test($('#address-input').val())){
            toast({
                title: 'Thất bại',
                message: 'Vui lòng nhập địa chỉ đúng định dạng. VD: 77 Phan Đình Giót.',
                type: 'error',
                duration: 5000
            });
            return;
        }

        toast({
            title: 'Thông báo',
            message: 'Đang cập nhật thông tin giao hàng...',
            type: 'info',
            duration: 10000
        });

        let province = $('#province-selectbox option:selected').text();
        let district = $('#district-selectbox option:selected').text();
        let ward = $('#ward-selectbox option:selected').text();
        let address = $('#address-input').val();
        let fullAddress = `${address}, ${ward}, ${district}, ${province}`;
        
        try {
            // Lấy trọng lượng (kg)
            const packageWeight = await getCartTotalWeight();

            // Tính phí vận chuyển
            const shippingFee = await calculateShippingFee(fullAddress, packageWeight);
            
            if (shippingFee === false) {
                toast({
                    title: 'Thất bại',
                    message: 'Địa chỉ không tồn tại',
                    type: 'error'
                });
            } else {
                $.ajax({
                    url: 'controller/client/CheckoutController.php',
                    type: 'get',
                    data: {
                        page: 'update-cart-info',
                        shippingFee: shippingFee,
                        fullAddress: fullAddress
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 'success') {
                            window.location.href = `index.php?page=checkout`;
                        } else {
                            toast({
                                title: 'Thất bại',
                                message: response.message,
                                type: response.status
                            });
                        }
                    },
                    error: function () {
                        toast({
                            title: 'Thất bại',
                            message: 'Không thể cập nhật phí vận chuyển',
                            type: 'error'
                        });
                    }
                });
            }
        } catch (error) {
            console.error('Lỗi:', error);
            toast({
                title: 'Thất bại',
                message: error,
                type: 'error'
            });
        }
    });


    async function calculateDistance(destination) {
        const apiKey = '5b3ce3597851110001cf624888a0e26465f6445bb059125278a23289';
        const origin = "273 An Dương Vương, Phường 02, Quận 5, Hồ Chí Minh";
        if (destination === origin) return 0;

        // Chuyển địa chỉ thành tọa độ
        const geocode = async (address) => {
            const response = await fetch(`https://api.openrouteservice.org/geocode/search?api_key=${apiKey}&text=${encodeURIComponent(address)}`);
            const data = await response.json();
            const coordinates = data.features[0].geometry.coordinates;
            return coordinates;
        };

        try {
            //Chuyển địa chỉ gốc và địa chỉ ship về tọa độ
            const originCoords = await geocode(origin);
            const destinationCoords = await geocode(destination);

            // Gọi API tính khoảng cách
            const response = await fetch('https://api.openrouteservice.org/v2/directions/driving-car', {
                method: 'POST',
                headers: {
                    'Authorization': apiKey,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    coordinates: [originCoords, destinationCoords],
                }),
            });

            const data = await response.json();
            const distance = data.routes[0].summary.distance / 1000; // Đơn vị là mét, chia cho 1000 để lấy km

            return distance;
        } catch (error) {
            console.error('Error:', error);
            return false;
        }
    }

    async function calculateShippingFee(destination, weight) {
        const tempDistance = await calculateDistance(destination);
        distance = parseInt(tempDistance);
        
        //Trả về false nếu hàm tính khoảng cách bị sai
        if (distance === false || isNaN(distance)) return false;

        var distanceFee = 0;

        if (distance == 0) return 0;

        if (distance <= 5) {
            distanceFee = 15000;
        } else if (distance > 5 && distance <= 20) {
            distanceFee = 20000;
        } else if (distance > 20 && distance <= 500) {
            distanceFee = 40000;
        } else {
            distanceFee = 30000;    //đi bằng máy bay :))
        }

        const weightFee = Math.max(weight, 1) * 1000;
        var totalDeliveryFee = distanceFee + weightFee;

        return totalDeliveryFee;
    }
});