$(document).ready(function() {
    initTaskDragAndDrop();
});

function initTaskDragAndDrop() {
    $('.tasks-template .task-dragndrop').each(function() {

        $(this).data('event', {
            title: $(this).attr('data-title'),
            task_id: $(this).attr('data-id'),
            stick: true
        });

        $(this).draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0,
            handle: '.move-widget',
            cursor: 'move',
            cursorAt: {left: 0, top: 0},
            tolerance: 'pointer',
            helper: function (e) {
                var original = $(e.target).hasClass("ui-draggable") ? $(e.target) : $(e.target).closest(".ui-draggable");

                clone = original.clone().css({
                    width: original.width()
                });
                clone.find('.move-widget').hide();

                var day_width = parseInt($('.fc-time-grid-container td.fc-widget-content:not(.fc-axis)').first().width()) + 1;
                var day_height = parseInt($('.fc-time-grid-container td.fc-widget-content:not(.fc-axis)').first().height()) + 1;

                clone.css('width', day_width).css('height', 100).css('opacity', 0.5).css('background', 'orange');

                return clone
            },
            appendTo: 'body'
        });
    });
}