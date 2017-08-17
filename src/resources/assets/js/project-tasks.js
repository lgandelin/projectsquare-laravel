$(document).ready(function() {

    //Toggle tasks list
    $('.project-tasks-template').on('click', '.phase .toggle-tasks', function() {
        $(this).toggleClass('glyphicon-triangle-bottom').toggleClass('glyphicon-triangle-top');
        var phase = $(this).closest('.phase');
        phase.find('.tasks').slideToggle();
    });

});