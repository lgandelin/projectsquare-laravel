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

    $('.phases-list').on('click', '.phase .phase-wrapper > .name', function() {
        var text = $(this).text();
        var phase = $(this).closest('.phase');
        $(this).text('').hide().after("<input type='text' class='input-phase-name' value=" + text + "><i class='valid-phase-rename glyphicon glyphicon-ok'></i> <i class='cancel-phase-rename glyphicon glyphicon-remove'></i>");
        phase.find('.input-phase-name').focus().val(text);
    });

    $('.phases-list').on('click', '.phase .valid-phase-rename', function() {
        var phase = $(this).closest('.phase');
        var text = phase.find('.input-phase-name').val();
        phase.attr('data-name', text);

        if (phase.attr('data-id') == "") {
            phase.attr('data-id', uniqid());
        }

        phase.find('.phase-wrapper > .name').text(phase.attr('data-name')).show();
        phase.find('.input-phase-name, i').remove();
    });

    $('.phases-list').on('click', '.phase .cancel-phase-rename', function() {
        var phase = $(this).closest('.phase');
        phase.find('.phase-wrapper > .name').text(phase.attr('data-name')).show();
        phase.find('.input-phase-name, i').remove();

        if (phase.attr('data-id') == "") {
            phase.remove();
        }
    });

    $('.phases-list').on('click', '.delete-phase', function() {
        if (confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
            var phase = $(this).closest('.phase');
            phase.remove();
        }

        return false;
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
        $(this).text('').hide().after("<input class='input-task-name' type='text' value=" + text + "><i class='valid-task-rename glyphicon glyphicon-ok'></i> <i class='cancel-task-rename glyphicon glyphicon-remove'></i>");
        task.find('.input-task-name').focus().val(text);
    });


    $('.phases-list').on('click', '.phase .task .valid-task-rename', function() {
        var task = $(this).closest('.task');
        var text = task.find('input[type="text"]').val();
        task.attr('data-name', text);

        if (task.attr('data-id') == "") {
            task.attr('data-id', uniqid());
        }

        task.find('.task-wrapper > .name').text(task.attr('data-name')).show();
        task.find('.input-task-name, i').remove();
    });

    $('.phases-list').on('click', '.phase .task .cancel-task-rename', function() {
        var task = $(this).closest('.task');
        task.find('.task-wrapper > .name').text(task.attr('data-name')).show();
        task.find('.input-task-name, i').remove();

        if (task.attr('data-id') == "") {
            task.remove();
        }
    });

    $('.phases-list').on('click', '.delete-task', function() {
        if (confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
            var task = $(this).closest('.task');
            task.remove();
        }

        return false;
    });

    //Phase and task validation by entering keys
    $('.phases-list').on('keydown', '.phase .phase-wrapper .input-phase-name', function (e) {
        if (e.which == 13) {
            var phase = $(this).closest('.phase');
            phase.find('.valid-phase-rename').trigger('click');
            return false;
        }

        if (e.which === 27) {
            var phase = $(this).closest('.phase');
            phase.find('.cancel-phase-rename').trigger('click');
            return false;
        }
    });

    $('.phases-list').on('keydown', '.phase .task-wrapper .input-task-name', function (e) {
        if (e.which == 13) {
            var task = $(this).closest('.task');
            task.find('.valid-task-rename').trigger('click');
            return false;
        }

        if (e.which === 27) {
            var task = $(this).closest('.task');
            task.find('.cancel-task-rename').trigger('click');
            return false;
        }
    });


    //Toggle tasks list
    $('.phases-list').on('click', '.phase .toggle-tasks', function() {
        $(this).toggleClass('glyphicon-triangle-bottom').toggleClass('glyphicon-triangle-top');
        var phase = $(this).closest('.phase');
        phase.find('.tasks').slideToggle();
    });
});