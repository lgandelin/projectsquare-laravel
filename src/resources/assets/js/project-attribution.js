$(document).ready(function() {
    initTasksDragAndDrop();

    $('.attribution-template').on('click', '.task .allocated .unallocate-task', function() {

        if (!confirm('Etes-vous sûrs de vouloir désattribuer cette tâche ?')) {
            return false;
        }

        var task = $(this).closest('.task');
        task.css('opacity', 0.8);

        var data = {
            task_id: task.attr('data-id'),
            filter_role: $('select[name="filter_role"]').val(),
            _token: $('#csrf_token').val()
        };

        $('#calendars .loading').show();

        var month_index = $('#months_index').val();

        $.ajax({
            type: "POST",
            url: route_task_unallocate,
            data: data,
            success: function(data) {
                task.find('.task-wrapper').removeClass('allocated');
                task.find('.avatar').remove();
                task.css('opacity', 1.0);
                initTasksDragAndDrop();
                task.find('.task-wrapper').draggable('enable');

                //Updates calendar
                $('#calendars .loading').hide();
                $('#calendars').html(data.calendars);

                //Reinit drag and drop
                initTasksDragAndDrop();

                //Reinit calendar
                initCalendarNavigation();
                displayMonth(month_index);
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message);
                task.css('opacity', 1.0);
            }
        });
    });
});

function initTasksDragAndDrop() {
    $('.task-wrapper:not(.allocated)').draggable({
        zIndex: 999,
        revert: function(valid) {
            $('.task-wrapper').css('opacity', 1);
            return true;
        },
        snap: '.user-day',
        revertDuration: 0,
        tolerance: 'pointer',
        //handle: '.drag-task',
        cursor: 'move',
        cursorAt: { left: 20, top: 20 },
        helper: function(e) {
            var original = $(e.target).hasClass("ui-draggable") ? $(e.target) :  $(e.target).closest(".ui-draggable");
            clone = original.clone().css({
                width: original.width()
            });
            original.css('opacity', 0.8);

            var day_width = parseInt($('.user-day').first().width())+2;
            var day_height = parseInt($('.user-day').first().height())+2;
            var task_duration = original.closest('.task').attr('data-duration');
            clone.css('width', task_duration * day_width).css('height', day_height).css('opacity', 0.5).css('background','orange').find('.name, .duration, .drag-task').hide();

            return clone
        },
        appendTo: 'body'
    });

    $('.occupation-template .user-day').droppable({
        accept: '.task-wrapper',
        drop: function (event, ui) {
            var task = ui.draggable.closest('.task');
            var task_id = task.attr('data-id');
            var user_id = $(this).attr('data-user');
            var day = $(this).attr('data-day');

            task.css('opacity', 0.8);

            var data = {
                name: task.attr('data-name'),
                allocated_user_id: user_id,
                start_time: day,
                duration: task.attr('data-duration'),
                task_id: task_id,
                filter_role: $('select[name="filter_role"]').val(),
                project_id: $('#project_id').val(),
                _token: $('#csrf_token').val()
            };

            if (ui.helper.offset().left != $(this).offset().left) {
                ui.helper.animate({ 'left': $(this).offset().left}, 500);
            }

            $('#calendars .loading').show();

            var month_index = $('#months_index').val();

            $.ajax({
                type: "POST",
                url: route_allocate_and_schedule_task,
                data: data,
                success: function(data) {
                    //Updates calendar
                    $('#calendars .loading').hide();
                    $('#calendars').html(data.calendars);

                    //Reinit drag and drop
                    initTasksDragAndDrop();

                    //Reinit calendar
                    initCalendarNavigation();
                    displayMonth(month_index);

                    //Update task in list
                    $('.task[data-id="' + task_id + '"]').css('opacity', 1).find('.task-wrapper').draggable('disable').addClass('allocated').prepend(data.avatar);
                },
                error: function(data) {
                }
            });

        },
        tolerance: 'pointer'
    });

    $('.occupation-template').on('mouseout', 'table', function() {
        $('.user-day').removeClass('preview');
    });
}