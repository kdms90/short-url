var foundationBack = {
    __refreshTable: function (data) {
        var table = $('#main-entities-table-body');
        if (table.length) {
            table.html(data);
        }
        foundationBack.showMoreDetailsOnTabList();
    },
    defaultsPlugins: function () {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
        });
    },
    /**
     * Recherche globale dans l'application
     */
    appSearchEngine: function () {
        var appSearchHost = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: mainAppSearchURI,
            remote: {
                url: mainAppSearchURI + '?q=%QUERY',
                wildcard: '%QUERY'
            }
        });
        appSearchHost.initialize();
        $('#app_main_search').typeahead({minLength: 0}, {
            source: appSearchHost.ttAdapter(),
            hint: true,
            highlight: true,
            limit: Infinity,
            displayKey: 'screen_name',
            //hint: $('.Typeahead-hint'),
            menu: $('.Typeahead-menu'),
            minLength: 0,
            classNames: {
                open: 'is-open',
                empty: 'is-empty',
                cursor: 'is-active',
                suggestion: 'Typeahead-suggestion',
                selectable: 'Typeahead-selectable'
            },
            templates: {
                empty: [
                    '<div class="ProfileCard u-cf Typeahead-suggestion Typeahead-selectable">' +
                    '            <span class="icon"><i class="ik ik-external-link"></i></span>' +
                    '            <div class="ProfileCard-details">' +
                    '                <div class="ProfileCard-realName">Aucun élément trouvé</div>' +
                    '                <div class="ProfileCard-screenName"></div>' +
                    '                <div class="ProfileCard-description">' +
                    '                    Aucun résultat n\'a été trouvé pour les mots clés entrés' +
                    '                </div>' +
                    '            </div>' +
                    '        </a>'
                ].join('\n'),
                suggestion: Handlebars.compile(
                    '<a href="{{path}}" class="ProfileCard u-cf Typeahead-suggestion Typeahead-selectable">' +
                    '            <span class="icon"><i class="{{ iconClass }}"></i></span>' +
                    '            <div class="ProfileCard-details">' +
                    '                <div class="ProfileCard-realName">{{ name }}</div>' +
                    '                <div class="ProfileCard-screenName"> - {{ module }}</div>' +
                    '                <div class="ProfileCard-description">' +
                    '                    {{ description }}' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '            <div class="ProfileCard-stats d-none">' +
                    '                <div class="ProfileCard-stat"><span class="ProfileCard-stat-label"></span></div>' +
                    '                <div class="ProfileCard-stat"><span class="ProfileCard-stat-label"></span></div>' +
                    '                <div class="ProfileCard-stat"><span class="ProfileCard-stat-label"></span></div>' +
                    '            </div>' +
                    '        </a>'
                )
            }
        }).on('typeahead:asyncrequest', function () {
            $('.Typeahead-spinner').show();
            $('.app-search-icon').addClass('d-none');
        }).on('typeahead:asynccancel typeahead:asyncreceive', function () {
            $('.Typeahead-spinner').hide();
            $('.app-search-icon').removeClass('d-none');
        });
    },
    loadAddOns: function () {
        //Permer d'ajouter un indice sur la tab ayant un champ avec une erreur
        var $hasErrors = $('.tab-pane .has-error');
        if ($hasErrors.length) {
            $hasErrors.each(function () {
                var $tab = $(this).parents('.tab-pane').eq(0);
                if ($tab.length) {
                    var $error = '<span class="color-red ml-2"><i class="fa fa-exclamation-circle"></i></span>';
                    var $id = '#' + $tab.attr('id');
                    var $tabTitle = $('a[href="' + $id + '"]');
                    if ($tabTitle.length) {
                        var $oldTitle = $tabTitle.html().replace($error, '');
                        $tabTitle.html($oldTitle + $error);
                    }
                }
            });
        }
        $(".dataTables_info").hide();
        foundationBack.iniTinymce();
    },
    iniTinymce: function (h) {
        tinymce.remove();
        h = typeof h !== 'undefined' ? h : 300;
        tinymce.init({
            selector: '.editor',
            language: 'fr_FR',
            automatic_uploads: false,
            setup: function (editor) {
                // editor.ui.registry.addButton('customInsertSlide', {
                //     text: 'Slide',
                //     tooltip: 'Inserer le module Slides',
                //     onAction: function (_) {
                //         editor.insertContent('&nbsp;<div class="xModClass">[blb-load-slideA]</div>&nbsp;');
                //     }
                // });
                // editor.ui.registry.addButton('customInsertCard', {
                //     text: 'Card',
                //     tooltip: 'Inserer le module Cards',
                //     onAction: function (_) {
                //         editor.insertContent('&nbsp;<div class="xModClass">[blb-load-cardB]</div>&nbsp;');
                //     }
                // });
                // editor.ui.registry.addButton('customInsertTab', {
                //     text: 'Tab',
                //     tooltip: 'Inserer le module Tabs',
                //     onAction: function (_) {
                //         editor.insertContent('&nbsp;<div class="xModClass">[blb-load-tabC]</div>&nbsp;');
                //     }
                // });
                // editor.ui.registry.addButton('customInsertTitle', {
                //     text: 'Titre',
                //     tooltip: 'Inserer un titre',
                //     onAction: function (_) {
                //         editor.insertContent('&nbsp;>[* PASTE YOUR TITLE *]&nbsp;');
                //     }
                // });
            },
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', tymceUploadImageURI);
                xhr.onload = function () {
                    var json;
                    if (xhr.status !== 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.file_path != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.file_path);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            height: h,
            theme: 'silver',
            plugins: 'print code preview emoticons importcss searchreplace autolink directionality imagetools visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount  imagetools contextmenu colorpicker textpattern help',
            toolbar1: 'undo redo | insert | formatselect | imagetools | bold italic strikethrough | forecolor backcolor  | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | customInsertSlide customInsertCard customInsertTab customInsertTitle',
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Poppins',
                'assets/libs/css/customCssEditor.css'
            ]
        });
    },
    topButtons: function () {
        $('#mainAppSaveButton').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $('.submit-main-form-btn').trigger('click');
        });
        $('#mainAppSaveAndStayButton').on('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $('.submit-main-form-btn-stay').trigger('click');
        });
    },
    handleImgOperations: function () {
        //Preview image
        $(document).on('change', ':file.previewImage', function () {
            var parent = $(this).parents('div.preview').eq(0);
            var preview = parent.find('.imageHolder');
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = preview;//$(".imageHolder");
            image_holder.empty();
            if (extn === "gif" || extn === "png" || extn === "jpg" || extn === "jpeg") {
                if (typeof (FileReader) !== "undefined") {
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img />", {
                                "src": e.target.result,
                                "class": "img-bordered img-responsive"
                            }).appendTo(image_holder);
                            $('<span class="remove-upload-image"><i class="fa fa-trash-o" title="Supprimer l\'image"></i></span>').appendTo(image_holder);
                        };
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[i]);
                    }

                } else {
                    //alert("This browser does not support FileReader.");
                }
            } else {
                //alert("Pls select only images");
            }
        });
        //Delete image
        $(document).on('click', 'body .remove-upload-image', function (e) {
            e.preventDefault();
            var image_holder = $(".imageHolder");
            image_holder.html(
                $("<img />", {
                    "src": defaultNoImageURI,
                    "class": "img-bordered img-responsive"
                }));
            $('.removeFile').val(1);
        })
    },
    switchLanguage: function () {
        $(document).on('click', '.bs-dropdown-to-select-group .dropdown-menu li', function (event) {
            var $li = $(this);
            var $activeContent = $li.attr('data-content');
            var $activeI18n = $li.find('a .lang-code-value').html();
            var $otherContents = '.edit-i18n-'+$activeI18n.toLowerCase();
            var $target = $(event.currentTarget);
            $target.closest('.bs-dropdown-to-select-group')
                .find('[data-bind="bs-drp-sel-value"]').html('<i class="ion-android-globe"></i> [' + $activeI18n + ']')
                .end()
                .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
                .find('[data-bind="bs-drp-sel-label"]').html('<i class="ion-android-globe"></i> [' + $activeI18n + ']');
            $('.edit-i18n').removeClass('d-none').addClass('d-none');
            $('.bs-drp-sel-label').html('<i class="ion-android-globe"></i> [' + $activeI18n + ']');
            $('#' + $activeContent).removeClass('d-none');
            $($otherContents).removeClass('d-none');
            return false;
        });
        $(document).find('.current-i18nuage').each(function (e) {
            var contentId = '#' + $(this).val();
            if ($(contentId).length) {
                $(contentId).removeClass('d-none');
            }
        });
    },
    setBulkItemsToDelete: function () {
        var $items = $('#bulk-items-to-delete');
        var $parent = $(document).find('table.bulk_action').eq(0);
        var $values = [];
        $parent.find('tbody .native-check').each(function () {
            if ($(this).is(":checked")) {
                // $values += ';'+$(this).val();
                $values.push($(this).val());
            }
        });
        $items.val($values);
    },
    overrideIcheckEvents: function () {
        //Exécution des actions en block
        var $actionGroupButton = $('#bulk-action-wrap');
        //Delete items
        $(document).on('click', '#bulk-delete-link', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            foundationBack.setBulkItemsToDelete();
            $actionGroupButton.find('form#bulk-action-form').trigger('submit');
        });
        $(document).on('click', '.bulk_action #check-all', function (e) {
            if ($(this).is(":checked")) {
                $actionGroupButton.removeClass('hide');
                $(this).trigger('ifChecked');
            } else {
                $(this).trigger('ifUnchecked');
                $actionGroupButton.addClass('hide');
            }
        });
        $(document).on('click', '.bulk_action tbody .native-check', function (e) {
            var $parent = $(this).parents('table.bulk_action');
            var $checkAll = $parent.find('#check-all');
            var $checked = true;
            $parent.find('tbody .native-check').each(function () {
                if ($(this).is(":checked") === false) {
                    $checked = false;
                }
            });
            $checkAll.attr('checked', $checked);
            var $hasOne = false;
            if ($(this).is(":checked")) {
                $hasOne = true;
            }
            if ($hasOne)
                $actionGroupButton.removeClass('hide');
            else
                $actionGroupButton.addClass('hide');
        });
        $(document).on('click', '#geographical-restrictions .native-check', function (e) {
            if ($(this).is(":checked"))
                $(this).trigger('ifChecked');
            else
                $(this).trigger('ifUnchecked');
        });
    },
    handleCheckContinentRestriction: function () {
        $(document).on('click', '#restricLocalization', function () {
            $(".slickRoles").slick({
                arrows: true,
                infinite: false,
                prevArrow: '<button class="slick-prev btn btn-default" type="button"><i class="ion-ios-arrow-left"></i></button>',
                nextArrow: '<button class="slick-next btn btn-default" type="button"><i class="ion-ios-arrow-right"></i></button>'
            });
        });
        $(document).on('ifChecked', '#geographical-restrictions .continentRestriction', function () {
            foundationBack.refreshCountriesRestrictionList();
        });
        $(document).on('ifUnchecked', '#geographical-restrictions .continentRestriction', function () {
            foundationBack.refreshCountriesRestrictionList();
        });
        //Check country
        $(document).on('ifChecked', '#geographical-restrictions .countryRestriction', function () {
            foundationBack.countCheckCountries();
            foundationBack.refreshCitiesRestrictionList();
        });
        $(document).on('ifUnchecked', '#geographical-restrictions .countryRestriction', function () {
            foundationBack.countCheckCountries();
            foundationBack.refreshCitiesRestrictionList();
        });
        //Check city
        $(document).on('ifChecked, click', '#geographical-restrictions .cityRestriction', function () {
            foundationBack.countCheckCities();
        });
        $(document).on('ifUnchecked', '#geographical-restrictions .cityRestriction', function () {
            foundationBack.countCheckCities();
        });
        foundationBack.countCheckCountries();
        foundationBack.countCheckCities();
    },
    refreshCountriesRestrictionList: function () {
        var $continentIds = [];
        var $countriesIds = [];
        var $countiesContainer = $('#countries-rules');
        $('#geographical-restrictions').find('.continentRestriction:checked').each(function () {
            $continentIds.push($(this).val());
        });
        $countiesContainer.find('.countryRestriction:checked').each(function () {
            $countriesIds.push($(this).val());
        });
        $countiesContainer.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
        $currentLoadActorsSuggestion = $.ajax({
            type: 'GET',
            headers: {"cache-control": "no-cache"},
            url: retrieveCountriesOfContinentURI,
            async: true,
            processData: false,
            cache: false,
            dataType: "JSON",
            data: $.param({
                'continents_ids': $continentIds,
                'checked_countries_ids': $countriesIds
            }),
            success: function (response, textStatus, jqXHR) {
                if (response.status === 1) {
                    $countiesContainer.empty();
                    $countiesContainer.html(response.addedCountries);
                    $("#" + response.slickIdentifier).slick(
                        {
                            arrows: true,
                            infinite: false,
                            prevArrow: '<button class="slick-prev btn btn-default" type="button"><i class="ion-ios-arrow-left"></i></button>',
                            nextArrow: '<button class="slick-next btn btn-default" type="button"><i class="ion-ios-arrow-right"></i></button>'
                        }
                    );
                    // if ($countiesContainer.find("input[type=checkbox]")[0]) {
                    //     $countiesContainer.find("input[type=checkbox]").iCheck({
                    //         checkboxClass: 'icheckbox_flat-green',
                    //         radioClass: 'iradio_flat-green'
                    //     });
                    // }
                    $countiesContainer.find('.country-name').each(function () {
                        $(this).tooltip({
                            title: $(this).attr('data-original-title'),
                            placement: $(this).attr('data-placement'),
                            container: 'body'
                        });
                    });
                    foundationBack.countCheckCountries();
                    //Refresh cities list
                    foundationBack.refreshCitiesRestrictionList();
                }
                $countiesContainer.removeClass('divLoader').removeClass('loading');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //alert("Impossible to add the consultant to the cart.\n\ntextStatus: '" + textStatus + "'\nerrorThrown: '" + errorThrown + "'\nresponseText:\n" + XMLHttpRequest.responseText);
            }
        });
    },
    refreshCitiesRestrictionList: function () {
        var $citiesIds = [];
        var $countriesIds = [];
        var $countiesContainer = $('#countries-rules');
        var $cytiesContainer = $('#cities-rules');
        $('#geographical-restrictions').find('.cityRestriction:checked').each(function () {
            $citiesIds.push($(this).val());
        });
        $countiesContainer.find('.countryRestriction:checked').each(function () {
            $countriesIds.push($(this).val());
        });
        $cytiesContainer.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
        $currentLoadActorsSuggestion = $.ajax({
            type: 'GET',
            headers: {"cache-control": "no-cache"},
            url: retrieveCitiesOfCountriesURI,
            async: true,
            processData: false,
            cache: false,
            dataType: "JSON",
            data: $.param({
                'countries_ids': $countriesIds,
                'checked_cyties_ids': $citiesIds
            }),
            success: function (response, textStatus, jqXHR) {
                if (response.status === 1) {
                    $cytiesContainer.empty();
                    $cytiesContainer.html(response.addedCyties);
                    $("#" + response.slickIdentifier).slick(
                        {
                            arrows: true,
                            infinite: false,
                            prevArrow: '<button class="slick-prev btn btn-default" type="button"><i class="ion-ios-arrow-left"></i></button>',
                            nextArrow: '<button class="slick-next btn btn-default" type="button"><i class="ion-ios-arrow-right"></i></button>'
                        }
                    );
                    // if ($cytiesContainer.find("input[type=checkbox]")[0]) {
                    //     $cytiesContainer.find("input[type=checkbox]").iCheck({
                    //         checkboxClass: 'icheckbox_flat-green',
                    //         radioClass: 'iradio_flat-green'
                    //     });
                    // }
                    $cytiesContainer.find('.city-name').each(function () {
                        $(this).tooltip({
                            title: $(this).attr('data-original-title'),
                            placement: $(this).attr('data-placement'),
                            container: 'body'
                        });
                    });
                    foundationBack.countCheckCities();
                }
                $cytiesContainer.removeClass('divLoader').removeClass('loading');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //alert("Impossible to add the consultant to the cart.\n\ntextStatus: '" + textStatus + "'\nerrorThrown: '" + errorThrown + "'\nresponseText:\n" + XMLHttpRequest.responseText);
            }
        });
    },
    countCheckCountries: function () {
        var checkCount = $("#geographical-restrictions").find("input.countryRestriction[type='checkbox']:checked").length;
        var $countContainer = $('#selectedCountriesNb');
        if (checkCount > 1)
            $countContainer.html(checkCount + ' sélectionnés');
        else
            $countContainer.html(checkCount + ' sélectionné');
    },
    countCheckCities: function () {
        var checkCount = $("#geographical-restrictions").find("input.cityRestriction[type='checkbox']:checked").length;
        var $countContainer = $('#selectedCitiesNb');
        if (checkCount > 1)
            $countContainer.html(checkCount + ' sélectionnées');
        else
            $countContainer.html(checkCount + ' sélectionnée');
    },
    launchPrompt: function () {
        var $notifyBox = $('#notifyBox');
        var $prompt = $('.app-notification');
        if ($notifyBox.length) {
            if (parseInt($notifyBox.attr('data-show-alert')) === 1) {
                foundationBack.showPrompt($notifyBox.find('.caption').html(), $notifyBox.find('.desc').html());
            }
        }
        $prompt.find('.dismissable-icon').on('click', function () {
            $prompt.removeClass('fadeInRight').addClass('slideOutRight');
        });
        var $helpPrompt = $('.top-help-block');
        $helpPrompt.find('.dismissable-icon').on('click', function () {
            $helpPrompt.addClass('d-none').removeClass('fadeInDown').addClass('slideOutRight');
        });
    },
    showPrompt: function ($title, $message) {
        var $prompt = $('#app-notification-js');
        $prompt.find('.msg-title').html($title);
        $prompt.find('.msg-body').html($message);
        $prompt.removeClass('d-none').removeClass('slideOutRight').addClass('fadeInDown');
    },
    selectAllOnTableList: function () {
        $("#check-all").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("input:checkbox").click(function () {
            if ($(this).is(":checked")) {
                var isAllChecked = 0;
                $("input:checkbox").each(function () {
                    if (!this.checked && !$(this).is("#check-all"))
                        isAllChecked = 1;
                })
                if (isAllChecked == 0) {
                    $("#check-all").prop("checked", true);
                }
            } else {
                $("#check-all").prop("checked", false);
            }
        });
    },
    highlightCurrentMenuItem: function () {
        var url = window.location.pathname;
        var nemuItem = $('.menu-item[href="' + url + '"]');
        nemuItem.addClass('active');
        nemuItem.parent().parent().addClass('active open');
        $('a[href="' + url + '"]').parent().addClass('active');
    },
    showMoreDetailsOnTabList: function () {
        $(document).on('click', '#main-entities-table-body .show-more-right-details', function (e) {
            e.preventDefault();
            var detailContent = $(document).find('#right-more-details-' + $(this).attr('data-id'));
            detailContent.addClass('open');
        });
        $(document).on('click', '.close-more-details', function (e) {
            e.preventDefault();
            var detailContent = $(document).find('#right-more-details-' + $(this).attr('data-id'));
            detailContent.removeClass('open');
        });
    },
    onAttachmentLoad: function () {
        $(document).on('change', '.custom-file-input', function (e) {
            var fileName = e.target.files[0].name;
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
    },
    handleSubMenuClick: () => {
        $(document).on('click', '.nav-item.has-sub > a', (e) => {
            e.preventDefault()
        });
    },
    handleDisabledMenuNavigation: () => {
        $(document).find('.menu-pagination a').addClass('disabled')
    },
    handleDriver: (steps) => {
        const defaultTips = [
            {
                element: '#header-top',
                popover: {
                    className: 'first-step-popover-class',
                    title: 'Search Bar',
                    description: 'This helps you to easily retrive some elements on application !',
                    position: 'bottom'
                }
            }, {
                element: '#app-header',
                popover: {
                    title: 'Toggle Fullscreen',
                    description: 'Activate and Desable Fullscreen view',
                    position: 'right'
                }
            },
        ];
        steps = typeof steps !== 'undefined' ? steps : defaultTips;
        const driver = new Driver();
        // Define the steps for introduction
        driver.defineSteps(steps);
        // Start the introduction
        $(document).on('click', '#help-driver', () => {
            setTimeout(() => {
                driver.start()
            }, 1);
        });
    },

    callFunctions: () => {
        foundationBack.appSearchEngine();
        foundationBack.showMoreDetailsOnTabList();
        foundationBack.selectAllOnTableList();
        foundationBack.onAttachmentLoad();
        foundationBack.highlightCurrentMenuItem();
        foundationBack.defaultsPlugins();
        foundationBack.loadAddOns();
        foundationBack.topButtons();
        foundationBack.launchPrompt();
        foundationBack.handleImgOperations();
        foundationBack.switchLanguage();
        foundationBack.overrideIcheckEvents();
        foundationBack.handleCheckContinentRestriction();
        foundationBack.handleSubMenuClick();
    }
};

$(document).ready(() => {
    foundationBack.callFunctions();
});