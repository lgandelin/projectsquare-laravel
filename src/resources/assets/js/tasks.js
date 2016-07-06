$(document).ready(function() {

    //VALID TASK
    $('.btn-valid-create-task').click(function() {
        $('#tasks').find('.alert').hide();

        if ($('.new-task').val() == '') {
            $('#tasks').find('.alert .text').text('Vous devez renseigner un titre pour cette tâche');
            $('#tasks').find('.alert').show();

            return false;
        }

        var data = {
            name: $('.new-task').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_task_create,
            data: data,
            success: function(data) {
                var html = loadTemplate('task-template', data.task);
                $('.tasks ul').append(html);
                $('.new-task').val('');
                update_tasks_count();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
            }
        });
    });

    //UPDATE TASK
    $('.tasks').on('click', '.task .name', function() {
        var task = $(this).closest('.task');
        var data = {
            task_id: task.data('id'),
            name: task.find('.name').val(),
            status: (task.attr('data-status') == 1) ? 0 : 1,
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_task_update,
            data: data,
            success: function(data) {
                $('.task[data-id="' + data.task.id + '"]').toggleClass('task-status-completed');
                $('.task[data-id="' + data.task.id + '"]').attr('data-status', (data.task.status == true) ? 1 : 0);
                update_tasks_count();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
            }
        });
    });

    //DELETE TASK
    $('.tasks').on('click', '.task .btn-delete-task', function() {
        var task_id = $(this).closest('.task').data('id');

        var data = {
            task_id: task_id,
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_task_delete,
            data: data,
            success: function(data) {
                $('.task[data-id="' + data.task.id + '"]').fadeOut().remove();
                update_tasks_count();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
            }
        });
    })
});

function update_tasks_count() {
    var count = $('.todo li:not(.task-status-completed)').length;

    $('.todo .tasks-number').text(count);

    if ($('.todo li').length == 0) {
        $('.no-tasks').show();
    } else {
        $('.no-tasks').hide();
    }
}