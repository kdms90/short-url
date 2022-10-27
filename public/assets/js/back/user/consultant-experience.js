var $experience = {
    _updadedFields: function () {
        var $experiencePeriod = $('#consultant_experience_isCurrent');
        var $fixedValue = $('#experience-end-date');
        $experiencePeriod.on('click', function (event) {
            $experience.__reloadForm($(this), $fixedValue);
        });
        $experience.__reloadForm($experiencePeriod, $fixedValue);
    },
    __reloadForm: function ($experiencePeriod, $fixedValue) {
        var $input = $experiencePeriod.find(".form-check input:checked").eq(0);
        if ($input.length) {
            var $v = parseInt($input.val());
            if ($v !== 1) {
                $fixedValue.removeClass('d-none');
            } else {
                $fixedValue.addClass('d-none');
                $fixedValue.find('select').val('').selectpicker('refresh');
            }
        } else {
            $fixedValue.addClass('d-none');
        }
    },
    _retrive: function ($url, $consultant_id, $page = 1, $parentDiv = '') {
        var $data = 'consultant_id=' + $consultant_id + '&page=' + $page;
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
                $('#added-experiences-table').find('tbody').html(response.entities);
                $('.select2entity[data-autostart="true"]').select2entity();
                foundation.resetLoading($parentDiv);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    },
    handle: function () {
        var $modal = $('#app-modal');
        //Récurapation de la liste des experience
        $(document).on('click', 'a.experiences', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('data-action');
            var $page = $(this).attr('data-page');
            var $consultant_id = parseInt($(this).attr('data-consultant'));
            $experience._retrive($url, $consultant_id, $page, $parentDiv);
        });
        //Récupération du formulaire
        $(document).on('click', '.add-experience-line', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('data-action');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            var $consultant_id = parseInt($(this).attr('data-consultant'));
            var $data = 'consultant_id=' + $consultant_id;
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
                    $modal.find('.modal-content').html(response.form);
                    $('.consultant_id').val($consultant_id);
                    $modal.modal({keyboard: false, backdrop: "static"});
                    $('.select2entity[data-autostart="true"]').select2entity();
                    $experience._updadedFields();
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.consultantExperienceForm', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $parentDiv = $(this).parents('div').eq(0);
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    $modal.find('.modal-content').html(response.form);
                    $experience._updadedFields();
                    if (response.status === 200) {
                        $('#added-experiences-table').find('tbody').html(response.entities);
                        foundation.showPrompt('Experience', 'Experience ajoutée avec succès');
                        $form.fadeOut('slow');
                        $modal.modal('toggle');
                    }
                    $parentDiv.removeClass('divLoader');
                    $parentDiv.removeClass('loading');
                    $parentDiv.children(".divModalLoader").remove();
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.loadPlugins();
                }
            })
        });
        //Suppression d'une entée
        $(document).on('click', '#experiences-lines .delete-experience-line', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            var $url = $(this).attr('data-action');
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
                        $('#added-experiences-table').find('tbody').html(response.entities);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
    },
};
export default $experience;