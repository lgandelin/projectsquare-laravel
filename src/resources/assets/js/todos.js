$(document).ready(function() {

    //VALID TASK
    $('.btn-valid-create-todo').click(function() {
        $('#todos').find('.alert').hide();

        if ($('.new-todo').val() == '') {
            $('#todos').find('.alert .text').text('Vous devez renseigner un titre pour cette tâche');
            $('#todos').find('.alert').show();

            return false;
        }

        var data = {
            name: $('.new-todo').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_todo_create,
            data: data,
            success: function(data) {
                var html = loadTemplate('todo-template', data.todo);
                $('.todos ul').append(html);
                $('.new-todo').val('');
                update_todos_count();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
            }
        });
    });

    //UPDATE TASK
    $('.todos').on('click', '.todo .name', function() {
        var todo = $(this).closest('.todo');
        var data = {
            todo_id: todo.data('id'),
            name: todo.find('.name').val(),
            status: (todo.attr('data-status') == 1) ? 0 : 1,
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_todo_update,
            data: data,
            success: function(data) {
                $('.todo[data-id="' + data.todo.id + '"]').toggleClass('todo-status-completed');
                $('.todo[data-id="' + data.todo.id + '"]').attr('data-status', (data.todo.status == true) ? 1 : 0);
                update_todos_count();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
            }
        });
    });

    //DELETE TASK
    $('.todos').on('click', '.todo .btn-delete-todo', function() {
        var todo_id = $(this).closest('.todo').data('id');

        var data = {
            todo_id: todo_id,
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_todo_delete,
            data: data,
            success: function(data) {
                $('.todo[data-id="' + data.todo.id + '"]').fadeOut().remove();
                update_todos_count();
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
            }
        });
    })
});

function update_todos_count() {
    var count = $('.todo li:not(.todo-status-completed)').length;

    $('.todo .todos-number').text(count);

    if ($('.todo li').length == 0) {
        $('.no-todos').show();
    } else {
        $('.no-todos').hide();
    }
}