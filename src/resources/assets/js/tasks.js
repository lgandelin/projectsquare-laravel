$(document).ready(function() {

    //Phase management

    $('.phases-list').on('click', '.add-phase', function() {
        var html = loadTemplate('phase-template', {
            id: '',
            name: '',
        });

        $('.phases-list .phases')
            .append(html)
            .find('.phase:last-child .name').trigger('click');
    });

    $('.phases-list').on('click', '.phase .name', function() {
        var text = $(this).text();
        var phase = $(this).closest('.phase');
        $(this).text('').hide().after("<input type='text' value=" + text + "><i class='valid-phase-rename glyphicon glyphicon-ok'></i> <i class='cancel-phase-rename glyphicon glyphicon-remove'></i>");
        phase.find('input[type="text"]').focus().val(text);
    });

    $('.phases-list').on('click', '.phase .valid-phase-rename', function() {
        var phase = $(this).closest('.phase');
        var text = phase.find('input[type="text"]').val();
        phase.attr('data-name', text);

        if (phase.attr('data-id') == "") {
            phase.attr('data-id', uniqid());
        }

        phase.find('.name').text(phase.attr('data-name')).show();
        phase.find('input[type="text"], i').remove();
    });

    $('.phases-list').on('click', '.phase .cancel-phase-rename', function() {
        var phase = $(this).closest('.phase');
        phase.find('.name').text(phase.attr('data-name')).show();
        phase.find('input[type="text"], i').remove();

        if (phase.attr('data-id') == "") {
            phase.remove();
        }
    });

    $('.phases-list').on('click', '.delete-phase', function() {
        var phase = $(this).closest('.phase');
        phase.remove();
    });


    //Task management

    $('.phases-list').on('click', '.phase .add-task', function() {
        var phase = $(this).closest('.phase');

        var html = loadTemplate('task-template', {
            id: '',
            name: '',
            phase: phase.attr('data-id')
        });

        var task = $(html);

        $('.phases-list .phase[data-id="' + phase.attr('data-id') + '"] .tasks .placeholder')
            .before(task);

        task.find('.name').trigger('click');
    });

    $('.phases-list').on('click', '.phase .task .name', function() {
        var text = $(this).text();
        var task = $(this).closest('.task');
        $(this).text('').hide().after("<input type='text' value=" + text + "><i class='valid-task-rename glyphicon glyphicon-ok'></i> <i class='cancel-task-rename glyphicon glyphicon-remove'></i>");
        task.find('input[type="text"]').focus().val(text);
    });

    $('.phases-list').on('click', '.delete-task', function() {
        var task = $(this).closest('.task');
        task.remove();
    });
});