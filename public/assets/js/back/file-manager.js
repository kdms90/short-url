var fileManager = {
    load: function () {
        //Lorsqu'on clique sur le lien pour voir la liste des consultants
        $(document).on('click', '.card-body-c .dropzone', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $('#file-manager-modal').modal({keyboard: false, backdrop: "static"});
        });
    },
    triggerUploadFile: function () {
        //Lorsqu'on clique sur le lien pour voir la liste des consultants
        $(document).on('click', '.card-body-c .dropzone', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $(this).parents('.card-body-c').find('.file-input input[type=file]').trigger('click');
        });
    },
    loadDragAndDrop: function ($iframe) {
        var $dropzon = $(document).find('.media-frame-content .dropzone');
        $dropzon.on('dragenter', function(ev) {
            // Entering drop area. Highlight area
            $dropzon.addClass("highlightDropArea");
        });

        $dropzon.on('dragleave', function(ev) {
            // Going out of drop area. Remove Highlight
            $dropzon.removeClass("highlightDropArea");
        });

        $dropzon.on('drop', function(ev) {
            // Dropping files
            ev.preventDefault();
            ev.stopPropagation();
            // iterate through files and upload it on server.
            if(ev.originalEvent.dataTransfer){
                if(ev.originalEvent.dataTransfer.files.length) {
                    var droppedFiles = ev.originalEvent.dataTransfer.files;
                    for(var i = 0; i < droppedFiles.length; i++)
                    {
                        $(".messages").append(" Dropped File "+ droppedFiles[i].name);
                        // Upload droppedFiles[i] to server
                        // $.post(); to upload file to server
                        var $data = new FormData();
                        $data.append('uploadedFiles',54545);
                        // var options = {
                        //     target:     '#divToUpdate',
                        //     data:     {uploadedFiles: droppedFiles[i] },
                        //     url:        uploadFilesURI,
                        //     success:    function() {
                        //         alert('Thanks for your comment!');
                        //     }
                        // };
                        // $.ajaxSubmit(options);
                        $.ajax({
                            type: 'POST',
                            headers: {"cache-control": "no-cache"},
                            url: uploadFilesURI,
                            data: {uploadedFiles: droppedFiles[i] },
                            async: true,
                            processData: false,
                            cache: false,
                            dataType: "JSON",
                            success: function (response, textStatus, jqXHR) {
                                $('#newsletter-save-template-to-use-modal-form').html(response.form);
                                $('#newsletter-save-template-to-use-modal').modal({keyboard: false, backdrop: "static"});
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("Une erreur inattendue s'est produite lors du traitement de votre requête. Merci de afraichir la page et essayez de nouveau.");
                            }
                        });
                    }
                }
            }

            $dropzon.removeClass("highlightDropArea");
            return false;
        });

        $dropzon.on('dragover', function(ev) {
            ev.preventDefault();
        });
    }
};
$(document).ready(function () {
    // fileManager.load();
    // fileManager.loadDragAndDrop();
    fileManager.triggerUploadFile();
});
function readURL(input) {
    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function(e) {
            $('.image-upload-wrap').hide();

            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();

            $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);

    } else {
        removeUpload();
    }
}

function removeUpload() {
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}
$('.image-upload-wrap').bind('dragover', function () {
    $('.image-upload-wrap').addClass('image-dropping');
});
$('.image-upload-wrap').bind('dragleave', function () {
    $('.image-upload-wrap').removeClass('image-dropping');
});