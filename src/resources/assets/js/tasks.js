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
                $('.tasks').append(html);
                $('.new-task').val('');
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message)
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
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message)
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
                $('.task[data-id="' + data.task.id + '"]').fadeOut();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message)
            }
        });
    })
});