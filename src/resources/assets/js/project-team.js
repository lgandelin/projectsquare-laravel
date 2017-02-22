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
    });
}