var profile = {
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
                $('.select2entity[data-autostart="true"]').select2entity();
                console.log('suis pas')
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
                    foundationBack.showPrompt('Profil', 'Profil mis à jour avec succès.');
                    $modal.modal('toggle');
                    $('#'+response.containerID).html(response.form);
                }
                foundation.resetLoading($parentDiv);
                $('.select2entity[data-autostart="true"]').select2entity();
                foundation.loadPlugins();
            }
        });
    },
    handle: function () {
        //Soumission pour approbation
        $(document).on('click', '.edit-profile-part a.link-edit', function (e) {
            e.preventDefault();
            var $url = $(this).attr('data-action');
            profile.__fetchForm($url);
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.actorProfileForm', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            profile.__submitForm($(this));
        });
    },
};

export default profile;