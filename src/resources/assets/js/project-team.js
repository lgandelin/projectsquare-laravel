$(document).ready(function() {
    initTasksDragAndDrop();
});

function initTasksDragAndDrop() {
    $('.task-wrapper:not(.disabled)').draggable({
        zIndex: 999,
        revert: function() {
            $('.task-wrapper').css('opacity', 1);
            return true;
        },
        revertDuration: 0,
        tolerance: "pointer",
        cursorAt: { left: 0 },
        helper: function(e) {
            var original = $(e.target).hasClass("ui-draggable") ? $(e.target) :  $(e.target).closest(".ui-draggable");
            clone = original.clone().css({
                width: original.width()
            });
            original.css('opacity', 0.8);
            return clone
        },
        appendTo: 'body',
        stop: function(event, ui) {
            $('.user-day').css('background', 'white');
        }
    });


    $('.occupation-template .user-day').droppable({
        accept: '.task-wrapper',
        over: function (event, ui) {
            $('.user-day').css('background', 'white');
            var task_duration = ui.draggable.closest('.task').attr('data-duration');
            var element = $(this);
            for (var i = 0; i < task_duration; i++) {
                element.css('background', 'orange');
                element = element.next();
            }
        },
        out: function(event, ui) {
        },
        drop: function (event, ui) {
            var task_id = ui.draggable.closest('.task').attr('data-id');
            var user_id = $(this).attr('data-user');
            var day = $(this).attr('data-day');

            console.log('Task : ' + task_id);
            console.log('User : ' + user_id);
            console.log('Day : ' + day);

            $('.user-day').css('background', 'white');
        },
        tolerance: 'pointer'
    });
}