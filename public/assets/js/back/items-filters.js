var itemsFilters = {
    showMoreFilters: function () {
        $(document).on('click', '.show-more-filters', function (e) {
            e.preventDefault();
            var detailContent = $(document).find('.left-more-filters');
            detailContent.find('.prospets-list').html('$response.results');
            detailContent.addClass('open');
        });
        $(document).on('click', '#left-more-filters .close-more-filters', function (e) {
            e.preventDefault();
            var detailContent = $(document).find('.left-more-filters');
            detailContent.removeClass('open');
        });
    },
};
$(document).ready(function () {
    itemsFilters.showMoreFilters();
});