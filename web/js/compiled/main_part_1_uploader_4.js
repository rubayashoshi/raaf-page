//$(document).ready(function() {
//    $(document).on('click', '.no-image' , function() {
//        //if any img-wrap has class called current-wrap, remove them and add to one that has been clicked
//        $(this).parent().parent().parent().find('.current_output').removeClass('current_output');
//        $(this).parent().addClass('current_output');
//
//        // trigger as file input box has been clicked though it is hidden
//        $(this).siblings(".image_file").trigger('click');
//    });
//
//
//    $(document).on('change', '.image_file' , function() {
//        $('#MyUploadForm').addClass('fileUploadPressed');
//        $('#MyUploadForm').submit();
//    });
//
//    $('#MyUploadForm').submit(function(event) {
//        if ($('#MyUploadForm').hasClass('fileUploadPressed')) {
//            event.preventDefault();
//            //console.log('parent' + $(this).parent().html());
//            var imageId = $(this).parent().find('.output').data('image-id');
//            $('#MyUploadForm').removeClass('fileUploadPressed');
//            console.log('previous event element name' + event.originalEvent.explicitOriginalTarget);
//            $(this).ajaxSubmit({
//                url: 'http://raaf-page.local/app_dev.php/seller/add/ajax-upload',
//                data: {'image_id': imageId},
//                target: '.current_output',   // target element(s) to be updated with server response
//                beforeSubmit: beforeSubmit,  // pre-submit callback
//                success: afterSuccess,  // post-submit callback
//                resetForm: true        // reset the form after successful submit
//            });
//            return false;
//        }
//    });
//
//    //delete image
//    $(document).on('click', '.img-wrap .close' , function() {
//        var parent = $(this).parent().parent();
//        var imageId = parent.find('.output').data('image-id');
//        $(document).find('#no_image_hidden').data('image-id', 1);
//        var noImageHtml = '<img src="/images/noimage.png" id="no-image" class="no-image">';
//        noImageHtml += '<input name="image_file" class="image_file" id="imageInput" type="file">';
//
//        $.post("http://raaf-page.local/app_dev.php/seller/add/delete-image/" + imageId,function() {
//            parent.find('.output').empty();
//            console.log('output:::::::::::' + noImageHtml);
//            parent.find('.output').append(noImageHtml);
//        });
//    });
//});
//
//function afterSuccess()
//{
//    console.log('after form submit');
//    //$('#submit-btn').show(); //hide submit button
//    $('#loading-img').hide(); //hide submit button
//    $(this).append('<span class="close">&times;</span>');
//}
//
////function to check file size before uploading.
//function beforeSubmit(){
//    console.log('before form submit');
//    //check whether browser fully supports all File API
//    if (window.File && window.FileReader && window.FileList && window.Blob)
//    {
//        if( !$('#imageInput').val()) //check empty input filed
//        {
//            $("#output").html("Are you kidding me?");
//            return false
//        }
//
//        var fsize = $('#imageInput')[0].files[0].size; //get file size
//        var ftype = $('#imageInput')[0].files[0].type; // get file type
//
//
//        //allow only valid image file types
//        switch(ftype)
//        {
//            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
//            break;
//            default:
//                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
//                return false
//        }
//
//        //Allowed file size is less than 1 MB (1048576)
//        if(fsize>1048576)
//        {
//            console.log('debug 5+++++++++++++');
//            $("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
//            return false
//        }
//
//        $('#submit-btn').hide(); //hide submit button
//        $('#loading-img').show(); //hide submit button
//        $("#output").html("");
//    }
//    else
//    {
//        //Output error to older browsers that do not support HTML5 File API
//        $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
//        return false;
//    }
//}
//
////function to format bites bit.ly/19yoIPO
//function bytesToSize(bytes) {
//    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
//    if (bytes == 0) return '0 Bytes';
//    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
//    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
//}
