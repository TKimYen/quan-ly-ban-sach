function checkSearch(){
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    let alert = '';
    if(minPrice == "" && maxPrice == "")
        alert = "Vui lòng nhập khoảng giá phù hợp";
    //minPrice
    else if(isNaN(minPrice) || minPrice<0)
        alert = "Giá bắt đầu phải là số dương lớn hơn 0";

    //maxPrice
    else if(isNaN(maxPrice) || maxPrice<0)
        alert = "Giá kết thúc phải là số dương lớn hơn 0";

    else if(parseFloat(minPrice)>parseFloat(maxPrice))
        alert = "Giá kết thúc phải lớn hơn giá bắt đầu";

    return alert;
}

document.getElementById("searchProduct_form").onsubmit = function (e) {
  // Perform validation
  let alert = checkSearch();

  if (alert !== "") {
    // Prevent form submission if validation fails
    e.preventDefault();
    toast({
      title: 'Lỗi',
      message: alert,
      type: 'error',
      duration: 3000
    });
  }
  // If validation passes, do nothing and let the form submit
};