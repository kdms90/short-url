var applications = {
    __fetchForm: function($url){
        var $parentDiv = $('#app');
        $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
        var $data = '';
        $.ajax({
            type: 'GET',
            headers: {"cache-control": "no-cache"},
            url: $url,
            data: $data,
            async: true,
            processData: false,
            cache: false,
            dataType: "JSON",
            success: function (response, textStatus, jqXHR) {
                var $modal = $('#app-modal');
                $modal.find('.modal-content').html(response.form);
                foundationBack.switchLanguage();
                $modal.modal({keyboard: false, backdrop: "static"});
                foundation.resetLoading($parentDiv);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    },
    __submitForm: function($form){
        var $parentDiv = $form.parents('div').eq(0);
        $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
        $form.ajaxSubmit({
            success: function (response) {
                var $modal = $('#app-modal');
                $modal.find('.modal-content').html(response.form);
                if (response.status === 200) {
                    foundationBack.showPrompt('Candidature', 'La candidature a été bien soumise pour approbation.');
                    $modal.modal('toggle');
                    $('#'+response.buttonID).addClass('d-none');
                }
                foundation.resetLoading($parentDiv);
                $('.select2entity[data-autostart="true"]').select2entity();
                foundation.loadPlugins();
            }
        });
    },
    handle: function () {
        //Soumission pour approbation
        $(document).on('click', 'a.application-status-action', function (e) {
            e.preventDefault();
            var $url = $(this).attr('href');
            applications.__fetchForm($url);
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.submitApplicationValidationForm', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            applications.__submitForm($(this));
        });
    },
};

export default applications;