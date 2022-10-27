var registration = {
    handleForm: function () {
        //Envoie du contact
        $(document).on('submit', '#registration-wrap form.authentication-form', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $parentDiv = $form.parents('div').eq(0);
            $parentDiv.addClass('divLoader loading').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    var $container = $('#registration-wrap');
                    console.log(response.redirectTo);
                    if(typeof  response.redirectTo !== 'undefined'){
                        window.location.replace(response.redirectTo);
                    }
                    $container.html(response.form);
                    foundation.resetLoading($parentDiv);
                    foundation.loadPlugins();
                }
            });
        });
    }
};

$(document).ready(function () {
    registration.handleForm();
});
