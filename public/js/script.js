jQuery(document).ready(function ($) {

    // loading all active customers 
    var currentPage = 1;

    function loadCustomers(page, search) {
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_active_customers',
                page: page,
                search: search
            },
            success: function (response) {
                $('#customer-list').html(response.data.customers);
                currentPage = page;
                updatePagination(response.data.totalPages, currentPage);
            }
        });
    }

    loadCustomers(currentPage, '');

    $('#customer-search').on('keyup', function () {
        var searchTerm = $(this).val();
        loadCustomers(1, searchTerm);
    });

    function updatePagination(totalPages, currentPage) {
        var pagination = $('#pagination');
        pagination.empty();
    
        if (totalPages <= 1) {
            return;
        }
    
        var maxPagesToShow = 10; // setting pagination per page item
        var startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        var endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
    
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    
        if (currentPage > 1) {
            var prevButton = $('<a href="#" class="prev" data-page="' + Math.max(1, currentPage - 1) + '">&laquo; Previous</a>');
            pagination.append(prevButton);
        }
    
        for (var i = startPage; i <= endPage; i++) {
            var linkClass = (i === currentPage) ? 'active' : '';
            var link = $('<a href="#" class="' + linkClass + '" data-page="' + i + '">' + i + '</a>');
            pagination.append(link);
        }
    
        if (currentPage < totalPages) {
            var nextButton = $('<a href="#" class="next" data-page="' + Math.min(totalPages, currentPage + 1) + '">Next &raquo;</a>');
            pagination.append(nextButton);
        }
    }
    
    $(document).on('click', '#pagination a', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        var searchTerm = $('#customer-search').val();
        loadCustomers(page, searchTerm);
    });
});
