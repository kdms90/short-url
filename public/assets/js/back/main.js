var main = {
    handle: function () {
        $(document).on('click', '.run-app-initialisation', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('data-action');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            var $data = '';
            $.ajax({
                type: 'POST',
                headers: {"cache-control": "no-cache"},
                url: $url,
                data: $data,
                async: true,
                processData: false,
                cache: false,
                dataType: "JSON",
                success: function (response, textStatus, jqXHR) {
                    if (response.status === 200) {
                        foundationBack.showPrompt('Mise en route', response.message);
                        $('#'+response.wrapContainerID).html(response.form);
                    }
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
        const driver = new Driver();
        driver.highlight('#notice-app-initialization');
    }
};
$(document).ready(function () {
    main.handle();
});