import consultantCertification from './user/consultant-certification.js';
import consultantEducation from './user/consultant-education.js';
import consultantExperience from './user/consultant-experience.js';
import consultantExpertise from './user/consultant-expertise.js';
import consultantSkill from './user/consultant-skill.js';
import invitation from './user/invitation.js';
import applications from './user/applications.js';
import profile from './user/profile.js';
var user = {
    handleCompany: function () {
        $(document).on('click', '#company-form-on-modal', function (e) {
            e.preventDefault();
            var $parentDiv = $('#app');
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            var $data = '';
            $.ajax({
                type: 'GET',
                headers: {"cache-control": "no-cache"},
                url: companyEditURI,
                data: $data,
                async: true,
                processData: false,
                cache: false,
                dataType: "JSON",
                success: function (response, textStatus, jqXHR) {
                    var $modal = $('#app-modal');
                    $modal.find('.modal-content').html(response.form);
                    $modal.modal({keyboard: false, backdrop: "static"});
                    $('.select2entity[data-autostart="true"]').select2entity();
                    foundation.resetLoading($parentDiv);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        });
        //Soumission du formulaire
        $(document).on('submit', '#app-modal form.companyForm', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $parentDiv = $(this).parents('div').eq(0);
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    var $modal = $('#app-modal');
                    $modal.find('.modal-content').html(response.form);
                    if (response.status === 200){
                        foundationBack.showPrompt('La société','La société a été bien mise à jour');
                        $form.fadeOut('slow');
                        foundationBack.__refreshTable(response.entities);
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
    },
};
$(document).ready(function () {
    user.handleCompany();
    consultantCertification.handle();
    consultantEducation.handle();
    consultantExperience.handle();
    consultantExpertise.handle();
    consultantSkill.handle();
    invitation.handle();
    applications.handle();
    profile.handle();
});