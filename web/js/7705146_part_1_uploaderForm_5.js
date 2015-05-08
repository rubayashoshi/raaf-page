var uploadForm = function () {
    this._attachEvents();
    this.imageId = null;
    this.form = $('#MyUploadForm');
};

uploadForm.prototype = {
    _attachEvents: function () {
        var _this = this;

        $(document).on('click', '.no-image' , function() {
            //if any img-wrap has class called current-wrap, remove them and add to one that has been clicked
            $(this).parent().parent().parent().find('.current_output').removeClass('current_output');
            $(this).parent().addClass('current_output');

            _this.imageId = $(this).parent().data('image-id');
            console.log('save image id in object' + _this.imageId);

            // trigger as file input box has been clicked though it is hidden
            $(this).siblings(".image_file").trigger('click');
        });

        $(document).on('change', '.image_file' , function() {
            _this.form.addClass('fileUploadPressed');
            _this.form.submit();
        });

        //handle form submission
        $('#MyUploadForm').submit(function(event) {
            if ($('#MyUploadForm').hasClass('fileUploadPressed')) {
                event.preventDefault();
                $('#MyUploadForm').removeClass('fileUploadPressed');

                $(this).ajaxSubmit({
                    url: 'http://raaf-page.local/app_dev.php/seller/add/ajax-upload',
                    data: {'image_id': _this.imageId},
                    target: '.current_output',
                    beforeSubmit: beforeSubmit,
                    success: afterSuccess,
                    resetForm: true
                });

                return false;
            }
        });

        //handle image removing
        $(document).on('click', '.img-wrap .close' , function() {
            var parent = $(this).parent().parent();
            var imageId = parent.find('.output').data('image-id');
            $(document).find('#no_image_hidden').data('image-id', imageId);

            var noImageHtml = '<img src="/images/noimage.png" id="no-image" class="no-image">';
            noImageHtml += '<input name="image_file" class="image_file" id="imageInput" type="file">';

            $.post("http://raaf-page.local/app_dev.php/seller/add/delete-image/" + imageId,function() {
                parent.find('.output').empty();
                parent.find('.output').append(noImageHtml);
            });
        });
    }
};

function afterSuccess()
{
    $('#loading-img').hide();
    $(this).append('<span class="close">&times;</span>');
}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
    if (window.File && window.FileReader && window.FileList && window.Blob)
    {
        if( !$('#imageInput').val()) //check empty input filed
        {
            $("#output").html("Are you kidding me?");
            return false
        }

        var fsize = $('#imageInput')[0].files[0].size; //get file size
        var ftype = $('#imageInput')[0].files[0].type; // get file type


        //allow only valid image file types
        switch(ftype)
        {
            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
            break;
            default:
                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
                return false
        }

        //Allowed file size is less than 1 MB (1048576)
        if(fsize>1048576)
        {
            $("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
            return false
        }

        $('#submit-btn').hide(); //hide submit button
        $('#loading-img').show(); //hide submit button
        $("#output").html("");
    }
    else
    {
        //Output error to older browsers that do not support HTML5 File API
        $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
        return false;
    }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Bytes';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

$(function () {
    new uploadForm();
});
