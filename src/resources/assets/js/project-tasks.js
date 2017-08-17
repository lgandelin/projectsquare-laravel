$(document).ready(function() {

    //Toggle tasks list
    $('.project-template').on('click', '.parent .toggle-childs', function() {
        $(this).toggleClass('glyphicon-triangle-bottom').toggleClass('glyphicon-triangle-top');
        var parent = $(this).closest('.parent');
        parent.find('.childs').slideToggle();
    });

});