/*
elements -> $('.element')
pagination -> class="pagination"
pageNumbers -> class="page-numbers"
*/
function pagination(paginationLength, elesPerPage, elements) {
    const pagination = $('.pagination');
    const pageNumbers = $('.page-numbers');

    function displayElements(currentPage) {
        elements.each(function (index) {
            const start = (currentPage - 1) * elesPerPage;
            const end = currentPage * elesPerPage;
            if (index >= start && index < end) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    }

    function updatePagination(currentPage) {
        const totalPages = Math.ceil(elements.length / elesPerPage);

        if (totalPages == 1) {
            pagination.addClass('d-none');
            return;
        }

        pageNumbers.empty();

        const halfWindow = Math.floor(paginationLength / 2);
        let startPage = Math.max(1, currentPage - halfWindow);
        let endPage = Math.min(totalPages, currentPage + halfWindow);

        if (currentPage - halfWindow < 1) {
            endPage = Math.min(totalPages, endPage + (halfWindow - (currentPage - 1)));
        }
    
        if (currentPage + halfWindow > totalPages) {
            startPage = Math.max(1, startPage - (currentPage + halfWindow - totalPages));
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageNumber = $('<button type="button"></button>').text(i).addClass('page-number');
            if (i === currentPage) {
                pageNumber.addClass('active');
            }

            pageNumber.on('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayElements(currentPage);
                updatePagination(currentPage);
            });

            pageNumbers.append(pageNumber);
        }

        pagination.removeClass('d-none');
    }

    return function display(currentPage) {
        displayElements(currentPage);
        updatePagination(currentPage);
    }
}