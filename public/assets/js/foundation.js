var foundation = {
    loadPlugins: function () {
        $(document).on('click', '#registration-wrap #short-copy-clipboard',function () {
            var link = $(this).attr('data-clipboard-text');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(link).select();
            document.execCommand("copy");
            $temp.remove();
            $('.press-copy').removeClass("d-none");
        })
    }
}
$(document).ready(function () {
    foundation.loadPlugins();
});
