const searchInput = document.querySelector('#search-input');

searchInput.addEventListener('keydown', function(e) {
    console.log(`Key pressed: ${e.key}`);
    if (e.key === 'Enter') {
        let searchKey = searchInput.value.toLowerCase();
        searchKey = searchKey.trim();
        console.log(searchKey);

        let tempArr = searchKey.split(' ').join('%%');
        console.log(tempArr);
        let des = searchInput.getAttribute('link-to') + '?search_key=' + tempArr;
        console.log(des);
        window.location.href =  des;
    }
});