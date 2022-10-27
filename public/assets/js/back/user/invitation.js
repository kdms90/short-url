var invitation = {
    handle: function () {
        $(document).on('click', '.create-invitation-form-on-modal', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('href');
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
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.invitationForm', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $parentDiv = $(this).parents('div').eq(0);
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    var $modal = $('#app-modal');
                    $modal.find('.modal-content').html(response.form);
                    if (response.status === 200) {
                        foundationBack.showPrompt('Invitation', 'Invitation enregistrée avec succès');
                        $form.fadeOut('slow');
                        foundationBack.__refreshTable(response.entities);
                        window.location.replace(response.redirectTo);
                        $modal.modal('toggle');
                    }
                    foundation.resetLoading($parentDiv);
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.loadPlugins();
                }
            })
        });
        invitation.__handleContact();
        invitation.__removeInvitationLine();
        invitation.__handleSendInvitation();
    },
    __handleContact: function () {
        $(document).on('click', '.add-invitation-contact', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('href');
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
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.invitationContactForm', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $parentDiv = $(this).parents('div').eq(0);
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    var $modal = $('#app-modal');
                    $modal.find('.modal-content').html(response.form);
                    if (response.status === 200) {
                        foundationBack.showPrompt('Contact', 'Contact enregistré avec succès');
                        $form.fadeOut('slow');
                        $('#invitation-invitation-contacts').html(response.entities);
                        $modal.modal('toggle');
                    }
                    foundation.resetLoading($parentDiv);
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.loadPlugins();
                }
            })
        });
    },
    __removeInvitationLine: function () {
        //Supression d'une ligne
        $(document).on('click', '#invitation-invitation-contacts a.remove-contact-line', function (e) {
            e.preventDefault();
            var $url = $(this).attr('href');
            var $tr = $(this).parents('tr').eq(0);
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
                        $tr.fadeOut();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
    },
    __handleSendInvitation: function () {
        //Ici, nous comptabilisons la facture
        $(document).on('click', '#send-contacts-invitation', function (e) {
            e.preventDefault();
            var $url = $(this).attr('data-action');
            var $form = $('.save-invitation');
            var $formWrap = $form.parents('div').eq(0);
            var $parentDiv = $('#app');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                url: $url,
                success: function (response) {
                    $formWrap.html(response.form);
                    if (response.status === 200) {
                        $('#accounting-invitation-form-on-modal').addClass('d-none');
                        $('#contact-to-invite').trigger('click');
                        $('#invitation-invitation-contacts').html(response.entities);
                        foundationBack.showPrompt('Invitation', 'L\'invitation a été bien envoyée');
                    }
                    foundation.resetLoading($parentDiv);
                    foundation.loadPlugins();
                    foundationBack.loadAddOns();
                }
            });
        });
    },
    handleInvitationUpdated: function () {
        //Ici, nous comptabilisons la facture
        $(document).on('submit', 'form.save-invitation', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $formWrap = $form.parents('div').eq(0);
            var $parentDiv = $('#app');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    $formWrap.html(response.form);
                    $('.select2entity[data-autostart="true"]').select2entity();
                    if (response.status === 200) {
                        foundationBack.showPrompt('Facture brouillon', 'Vous avez initialisé la facture avec succès!');
                    }
                    foundation.resetLoading($parentDiv);
                    foundation.loadPlugins();
                }
            });
        });
    },
};

export default invitation;