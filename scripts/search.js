$('#search-btn').on('click', function() {
    let searchKey = $('#search-input').val().toLowerCase();
    
    if (searchKey == '') {
        return;
    }
    
    searchKey = searchKey.trim();
    console.log(searchKey);

    let tempArr = searchKey.split(' ').join('+');
    console.log(tempArr);
    let des = $('#search-input').attr('link-to') + '?search_key=' + tempArr;
    console.log(des);
    window.location.href =  des;
});