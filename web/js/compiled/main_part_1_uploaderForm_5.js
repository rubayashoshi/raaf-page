var uploadForm = function () {
    this._attachEvents();
    this.imageId = null;
    this.form = $('#MyUploadForm');
    this.propertyId = $('#save').data('property-id');
    this.dataTypeValidator = new dataTypeValidation();

    this.rentElement = $('#property_form_rent');
    this.titleElement = $('#property_form_title');
    this.descriptionElement = $('#property_form_description');
    this.linkElement = $('#property_form_link');
    this.emailElement = $('#property_form_contact_email_address');
    this.phoneElement = $('#property_form_contact_phone_number');
    this.contactNameElement = $('#property_form_contact_name');
    this.propertyTypeElement = $('#property_form_property_type');
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
                //event.preventDefault();
                $('#MyUploadForm').removeClass('fileUploadPressed');

                $(this).ajaxSubmit({
                    url: 'http://raaf-page.local/app_dev.php/seller/add/ajax-upload',
                    data: {'image_id': _this.imageId, 'property_id': parseInt(_this.propertyId)},
                    target: '.current_output',
                    cache: false,
                    beforeSubmit: beforeSubmit,
                    success: afterSuccess,
                    resetForm: false
                });

                return false;
            } else {
                //event.preventDefault();
                _this._validateOnSubmit(event);
            }
        });

        //handle image removing
        $(document).on('click', '.img-wrap .close' , function(event) {
            event.preventDefault();
            var parent = $(this).parent().parent();
            var imageId = parent.find('.output').data('image-id');
            $(document).find('#no_image_hidden').data('image-id', imageId);
            var arr = imageId.split('/');

            if ($('#save').data('property-id') > 0) {
                imageId = arr[2];
            }

            var noImageHtml = '<img height="100" width="100" src="/images/noimage.png" id="no-image" class="no-image">';
            noImageHtml += '<input name="image_file" class="image_file" id="imageInput" type="file">';

            $.post("http://raaf-page.local/app_dev.php/seller/add/delete-image/" + imageId,{'property_id': parseInt(_this.propertyId)},function() {
                parent.find('.output').empty();
                parent.find('.output').append(noImageHtml);
            });
        });
    },
    _addErrorMessage: function (element, message) {
        var errorElement = element.parent().find("span")
        errorElement.removeClass("error").addClass("error_show");
        errorElement.empty().text(message);
    },
    _validateOnSubmit: function (event) {
        console.log('form has been submitted....');
        var error_free=true;
        var _this = this;
        $(document).find('span').removeClass('error_show').addClass('error');

        if (!_this.titleElement.val()) {
            error_free = false;
            _this._addErrorMessage(_this.titleElement, 'Title field is mandatory.');
        }

        if (!$(_this.descriptionElement).val()){
            error_free = false;
            _this._addErrorMessage(_this.descriptionElement, 'Description field is mandatory.');
        } else {
            if (_this.descriptionElement.val().length < 100) {
                error_free = false;
                _this._addErrorMessage(_this.descriptionElement, 'Description is too short, minimum 100 characters required.');
            }
        }

        if (!_this.rentElement.val()) {
            error_free = false;
            _this._addErrorMessage(_this.rentElement, 'Rent field is required.');
        } else {
            if (!_this.dataTypeValidator.isNumeric(_this.rentElement.val())) {
                error_free = false;
                _this._addErrorMessage(_this.rentElement, 'Invalid rent.');
            }
        }

        if (_this.linkElement.val()) {
            if (!_this.dataTypeValidator.isValidUrl(_this.linkElement.val())) {
                error_free = false;
                _this._addErrorMessage(_this.linkElement, 'Invalid web link.');
            }
        }

        if (!_this.dataTypeValidator.isEmailValid(_this.emailElement.val())) {
            error_free = false;
            _this._addErrorMessage(_this.emailElement, 'Invalid email address.');
        }

        if (!_this.dataTypeValidator.isPhoneValid(_this.phoneElement.val())) {
            error_free = false;
            _this._addErrorMessage(_this.phoneElement, 'Invalid phone number.');
        }

        if (!_this.contactNameElement.val()) {
            error_free = false;
            _this._addErrorMessage(_this.contactNameElement, 'Contact name field is mandatory.');
        }

//        if (!_this.propertyTypeElement.find('input[type="radio"]').val()) {
//            error_free = false;
//            _this._addErrorMessage(_this.contactNameElement, 'Contact name field is mandatory.');
//        }
//

//        //validate contact email
//        if ($(this).attr('name').indexOf('contact_email_address') != -1) {
//            if ($(this).val()) {
//                if ($(this).val() && !_this.dataTypeValidator.isEmailValid(inputData)) {
//                    error_free = false;
//                    _this._addErrorMessage(errorElement, 'Oops, invalid email address!.');
//                }
//            }
//        }
//
//        if ($(this).attr('name').indexOf('contact_phone_number') != -1) {
//            if ($(this).val()) {
//                if ($(this).val() && !_this.dataTypeValidator.isEmailValid(inputData)) {
//                    error_free = false;
//                    _this._addErrorMessage(errorElement, 'Oops, invalid email address!.');
//                }
//            }
//        }
//
//        $("property_form_description").each(
//            function () {
//                var errorElement = $(this).parent().find("span");
//
//                if (!$(this).val()) {
//                    error_free = false;
//                    errorElement.removeClass("error").addClass("error_show");
//                } else {
//                    errorElement.removeClass("error_show").addClass("error");
//                }
//            }
//        );
//
//        $( "input[name^='propedddrty_form']").each(
//            function () {
//                var errorElement = $(this).parent().find("span");
//                var inputData = $(this).val();
//
//                if (!$(this).val()) {
//                    error_free = false;
//                    errorElement.removeClass("error").addClass("error_show");
//                } else {
//                    errorElement.removeClass("error_show").addClass("error");
//                }
//
//            }
//        );



//        for (var input in form_data){
//            var element=$("#MyUploadForm" + form_data[input]['name']);
//            var valid=element.hasClass("valid");
//            var error_element=$("span", element.parent());
//            if (!valid){
//                console.log('add class error_show' + error_element.html());
//                error_element.removeClass("error").addClass("error_show");
//                error_free=false;
//            }
//            else{error_element.removeClass("error_show").addClass("error");}
//        }
        if (!error_free){
            console.log('form has error, hold on..');
            event.preventDefault();
        }
        else{
            console.log('form validation passed.....');
            alert('No errors: Form will be submitted');
        }
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
