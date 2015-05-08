$(document).ready(function() {
    console.log('loading drop down menu');
    $( '.dropdown' ).hover(
        function(){
            $(this).children('.sub-menu').slideDown(100);
        },
        function(){
            $(this).children('.sub-menu').slideUp(100);
        }
    );
});