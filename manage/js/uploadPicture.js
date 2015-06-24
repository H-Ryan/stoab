/**
 * Created by Samuil on 24-06-2015.
 */

$(document).ready(function() {
    $("#btnUploadPicture").click(function() {
        $(this).addClass('loading');
        $("#uploadProgress").show();
        sendFile($("#uploadPictureForm").find("#fileUpload").prop('files')[0]);
    });
    $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
    });
    $("#uploadProgress").progress({
            percent: 0,
            total: 100,
            text: {
                success : 'The photo has been uploaded!'
            }
        });
    $('.message').flowtype({
        minFont : 12,
        maxFont : 40
    });
});

function sendFile(file) {
    var form_data = new FormData();
    form_data.append('file', file);
    $.ajax({
        xhr: function(){
            var xhr = $.ajaxSettings.xhr() ;
            xhr.upload.onprogress = function(evt){ $('#uploadProgress').progress({ percent: evt.loaded/evt.total*100 }); } ;
            return xhr;
        },
        async: true,
        url: 'src/misc/uploadImage.php',
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'POST',
        success: function(data){

            setTimeout(function() {
                var loader = $('#uploadProgress');
                loader.progress({ percent: 0 });
                loader.hide();
                $("#btnUploadPicture").removeClass('loading');
            },1000);
            var form = $("#uploadPictureForm");
            var message = null;
            if (data.error == 0) {
                message = form.find(".success.message");
                $.each(data.message, function(i, v) {
                    message.find('p').append($("<span>"+v+"</span><br />"));
                });
                message.show();
                setTimeout(function() {
                    var file = form.find("#fileUpload").prop('files')[0];
                    if (file) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $(".cards.uploadedImages").prepend("<div class='ui blue card'><div class='bordered image'><img src='"+e.target.result+"'></div><div class='extra content'><p class='header'>Vägen till bilden:</p><div class='description'><span class='ui compact message'>images/uploaded/"+file.name+"</span></div></div></div>");
                        };

                        reader.readAsDataURL(file);
                        $("#noUploadedPictures").hide();
                    }
                },1000);

            } else {
                message = form.find(".error.message");
                $.each(data.message, function(i, v) {
                    message.find('p').append($("<span>"+v+"</span><br />"));
                });
                message.show();
            }
        }
    });
}