const searchInput = document.querySelector('#search-input');

searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        let searchKey = searchInput.value.toLowerCase();
        searchKey = searchKey.trim();
        console.log(searchKey);

        let tempArr = searchKey.split(' ').join('%%');
        let des = searchInput.getAttribute('link-to') + '?search_key=' + tempArr;
        console.log(des);
        window.location.href =  des;
    }
});