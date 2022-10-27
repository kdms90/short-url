var contextEception = {
    loadForm: function () {
        $('#wrong-context-modal').modal({keyboard: false, backdrop: "static"});
    },
    set: function () {
        //Soumission du formulaire
        $(document).on('submit', '#fix-wrong-context', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $form = $(this);
            var $parentDiv = $(this).parents('div').eq(0);
            $parentDiv.addClass('divLoader').prepend('<div class="divModalLoader"></div>');
            $form.ajaxSubmit({
                success: function (response) {
                    $parentDiv.html(response.form);
                    if(response.status === 1){
                        setTimeout(function() { window.location.replace(response.redirectTo); }, 3000);
                    }
                    $parentDiv.removeClass('divLoader');
                    $parentDiv.removeClass('loading');
                    $parentDiv.children(".divModalLoader").remove();
                    foundation.loadPlugins();
                }
            })
        });
    },
};
$(document).ready(function () {
    contextEception.set();
    contextEception.loadForm();
});