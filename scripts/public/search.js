$('#search-btn').on('click', function() {
    let searchKey = $('#search-input').val().toLowerCase();
    
    if (searchKey == '') {
        return;
    }
    
    searchKey = searchKey.trim();
    console.log(searchKey);

    let tempArr = searchKey.split(' ').join('+');
    console.log(tempArr);
    //http://localhost/tech_house/
    // let des = 'http://localhost:3001/public/product_list.php?search_key=' + tempArr;
    let des = window.location.origin + '/tech_house/public/product_list.php?search_key=' + tempArr;
    console.log(des);
    window.location.href =  des;
});