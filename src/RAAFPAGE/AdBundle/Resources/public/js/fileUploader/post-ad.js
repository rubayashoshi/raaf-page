var postAnAd = function () {
    console.log('postAnAd class loaded');
    this._initialize();
};

postAnAd.prototype = {
    _initialize: function(){
        $('.category').click(function () {
            var id = $(this).attr('id');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var idForVisibleContent = id + '-content';
            $(".sub-category").addClass('hide');
            $('#' + idForVisibleContent).removeClass('hide');
        });

        $('.subcategory').click(function () {
            var id = $(this).attr('id');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var idForVisibleContent = id + '-content';
            $(".subcategory-container").addClass('hide');
            $('#' + idForVisibleContent).removeClass('hide');
        });

        $('.subsubcategory').click(function () {
            var id = $(this).attr('id');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var idForVisibleContent = id + '-content';
            $(".ads").addClass('hide');
            $('#' + idForVisibleContent).removeClass('hide');
        });
    }
};

$(function () {
    new postAnAd();
});
