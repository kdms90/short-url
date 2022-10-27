var business = {
    handleGroupEntityService: function () {
        $(document).on('click', '.group-entity-service-form-on-modal', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $entityId = $('#entity_id').val();
            const url = $(this).attr('data-uri');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $.ajax({
                type: 'GET',
                headers: {"cache-control": "no-cache"},
                url: url,
                async: true,
                processData: false,
                cache: false,
                dataType: "JSON",
                success: function (response, textStatus, jqXHR) {
                    var $modal = $('#app-modal');
                    $modal.find('.modal-content').html(response.form);
                    $modal.find('#app_business_group_entity_service_entity_id').val($entityId);
                    $modal.modal({keyboard: false, backdrop: "static"});
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //$modal.modal({keyboard: false, backdrop: "static"});
                    foundation.resetLoading($parentDiv);
                }
            });
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.serviceForm', function (e) {
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
                        foundationBack.showPrompt('Service', 'Service bien enregistré');
                        $form.fadeOut('slow');
                        $modal.modal('toggle');
                        $(document).find('#groupe-entity-services tbody').html(response.data);
                    }
                    $parentDiv.removeClass('divLoader');
                    $parentDiv.removeClass('loading');
                    $parentDiv.children(".divModalLoader").remove();
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.loadPlugins();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //$modal.modal({keyboard: false, backdrop: "static"});
                    foundation.resetLoading($parentDiv);
                }
            })
        });
    },

    handleEditGroupEntityService: function () {
        $(document).on('click', '.group-entity-service-edit-form-on-modal', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $entityId = $('#entity_id').val();
            var $entityDeliverableEditURI = $(this).attr('data-link');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            var $data = '';
            $.ajax({
                type: 'GET',
                headers: {"cache-control": "no-cache"},
                url: $entityDeliverableEditURI,
                data: $data,
                async: true,
                processData: false,
                cache: false,
                dataType: "JSON",
                success: function (response, textStatus, jqXHR) {
                    var $modal = $('#app-modal');
                    $modal.find('.modal-content').html(response.form);
                    $modal.find('#app_business_group_entity_service_entity_id').val($entityId);
                    $modal.modal({keyboard: false, backdrop: "static"});
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //$modal.modal({keyboard: false, backdrop: "static"});
                    foundation.resetLoading($parentDiv);
                }
            });
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.entityDeliverableEditForm', function (e) {
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
                        foundationBack.showPrompt('Service', 'Service bien Modifié');
                        $form.fadeOut('slow');
                        $modal.modal('toggle');
                        $('#groupe-entity-services tbody').html(response.data);
                    }
                    $parentDiv.removeClass('divLoader');
                    $parentDiv.removeClass('loading');
                    $parentDiv.children(".divModalLoader").remove();
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.loadPlugins();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //$modal.modal({keyboard: false, backdrop: "static"});
                    foundation.resetLoading($parentDiv);
                }
            })
        });
        //Suppression d'une entée
        $(document).on('click', '#groupe-entity-services .group-entity-item-delete-form-on-modal', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('data-link');
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
                        foundationBack.showPrompt('Service', 'Service bien supprimé');
                        $(document).find('#groupe-entity-services tbody').html(response.data);
                    }
                    $parentDiv.removeClass('divLoader');
                    $parentDiv.removeClass('loading');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
    },

};


$(document).ready(function () {
    business.handleGroupEntityService();
    business.handleEditGroupEntityService();
});
