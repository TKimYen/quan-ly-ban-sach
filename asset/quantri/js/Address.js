$(document).ready(function() {
    // khoi tao
    $.ajax({
        url: '../controller/AddressController.php', // Replace with the actual PHP endpoint to fetch user details
        type: 'POST',
        data: {
            'province_data': true,
        },
        success: function(response){
            const obj = JSON.parse(response);
            $.each(obj, function(index, district) {
                $('#supplier-district').append(
                    $('<option></option>').val(district.idQuan).text(district.tenQuan)
                );
            });
        },
    });

    // event on city change 
    // event on district change
});