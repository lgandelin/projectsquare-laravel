var formModified = 0;

$(document).ready(function() {

    //Inputs
    $('.input-phase-name, .input-task-name').autosizeInput();

    var textarea = $('#import-phases-tasks-modal textarea[name="text"]');

    if (textarea.val() == '') {
        textarea.val(textarea.attr('data-placeholder')).addClass('placeholder');
    }

    textarea.on('focus', function() {
        if (textarea.val() == textarea.attr('data-placeholder')) {
            textarea.val('').removeClass('placeholder');
        }
    });

    textarea.on('blur', function() {
        if (textarea.val() == '') {
            textarea.val(textarea.attr('data-placeholder')).addClass('placeholder');
        }
    });

    //Phase management
    $('.project-tasks').on('click', '.add-phase', function() {
        formModified = 1;
        var html = loadTemplate('phase-template', {
            id: uniqid(),
            name: '',
        });

        $('.project-tasks .phases')
            .append(html)
            .find('.phase:last-child .input-phase-name').focus();

        //Tasks sortable
        init_tasks_sortable();
    });

    $('.project-tasks').on('click', '.delete-phase', function() {
        formModified = 1;
        if (confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
            var phase = $(this).closest('.phase');
            if ($(phase).attr('data-temp') != "1")
                $('#phase_ids_to_delete').val($('#phase_ids_to_delete').val()+phase.attr('data-id')+',');
            phase.remove();
        }

        return false;
    });

    //Task management
    $('.project-tasks').on('click', '.phase .add-task', function() {
        formModified = 1;
        var phase = $(this).closest('.phase');
        var html = loadTemplate('task-template', {
            id: uniqid(),
            name: '',
            phase: phase.attr('data-id')
        });

        var task = $(html);
        phase.find('.tasks .placeholder')
            .before(task);

        task.find('.input-task-name').focus();
    });

    $('.project-tasks').on('click', '.delete-task', function() {
        formModified = 1;
        if (confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
            var task = $(this).closest('.task');
            var phase = $(this).closest('.phase');
            if ($(task).attr('data-temp') != "1")
                $('#task_ids_to_delete').val($('#task_ids_to_delete').val()+task.attr('data-id')+',');
            task.remove();
            update_phase_duration(phase.attr('data-id'));
        }

        return false;
    });

    $('.project-tasks').on('focusout', '.input-task-duration', function() {
        formModified = 1;
        var phase = $(this).closest('.phase');
        update_phase_duration(phase.attr('data-id'));
    });

    //Toggle tasks list
    $('.project-tasks').on('click', '.phase .toggle-tasks', function() {
        $(this).toggleClass('glyphicon-triangle-bottom').toggleClass('glyphicon-triangle-top');
        var phase = $(this).closest('.phase');
        phase.find('.tasks').slideToggle();
    });

    //Phases sortable
    init_phases_sortable();

    //Tasks sortable
    init_tasks_sortable();

    //Validate
    $('.project-tasks .valid-phases').click(function() {
        formModified = 0;

        $('.valid-phases').hide();
        $('.loading').show();
        var phases = [];

        $('.project-tasks .phase').each(function() {
            var tasks = [];

            $(this).find('.task').each(function() {
                var task = {
                    id: $(this).attr('data-id'),
                    name: $(this).find('.input-task-name').first().val(),
                    duration: $(this).find('.input-task-duration').first().val(),
                    is_new: $(this).attr('data-temp')
                };

                tasks.push(task);
            });

            var phase = {
                id: $(this).attr('data-id'),
                name: $(this).find('.input-phase-name').first().val(),
                is_new: $(this).attr('data-temp'),
                tasks: tasks
            };

            phases.push(phase);
        });

        var data = {
            project_id: $('#project_id').val(),
            phases: JSON.stringify(phases),
            phase_ids_to_delete: $('#phase_ids_to_delete').val(),
            task_ids_to_delete: $('#task_ids_to_delete').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_udpate_tasks,
            data: data,
            success: function(data) {
                window.location.reload();
            },
            error: function(data) {
                window.location.reload();
            }
        });
    });

    //Lose focus on enter
    $(document).keypress(function(e) {
        if (e.which == 13 && !$('textarea[name="text"]').is(':focus')) {
            $(':focus').blur();
        }
    });

    $('.button-import-phases-tasks').click(function() {
        $('#import-phases-tasks-modal').modal();
    });

    //Warn before leaving the page
    window.onbeforeunload = confirmExit;

    function confirmExit() {
        if (formModified == 1) {
            return "Vous n\'avez pas sauvegardé vos modifications, êtes-vous sûrs de vouloir quitter cette page ?";
        }
    }
});

function init_phases_sortable() {
    $('.project-tasks .phases').sortable({
        items: ".phase:not(.placeholder)",
        tolerance: 'pointer',
        start: function(event, ui) {
            formModified = 1;
        },
    });
}

function init_tasks_sortable() {
    $('.project-tasks .phase .tasks').sortable({
        items: ".task:not(.placeholder)",
        tolerance: 'pointer',
        helper: "clone",
        start: function(event, ui) {
            formModified = 1;
        },
    });
}

function update_phase_duration(phase_id) {
    var phase = $('.phase[data-id="' + phase_id + '"]');
    var phase_duration = 0;
    phase.find('.task').each(function() {
        var task_duration = parseFloat($(this).find('.input-task-duration').val());
        if (!isNaN(task_duration))
            phase_duration += task_duration
    });

    phase.attr('data-duration', phase_duration);
    phase.find('.phase-duration .value').text(phase_duration);
}

//Plugin autosize input
var Plugins;(function(n){var t=function(){function n(n){typeof n=="undefined"&&(n=30);this.space=n}return n}(),i;n.AutosizeInputOptions=t;i=function(){function n(t,i){var r=this;this._input=$(t);this._options=$.extend({},n.getDefaultOptions(),i);this._mirror=$('<span style="position:absolute; top:-999px; left:0; white-space:pre;"/>');$.each(["fontFamily","fontSize","fontWeight","fontStyle","letterSpacing","textTransform","wordSpacing","textIndent"],function(n,t){r._mirror[0].style[t]=r._input.css(t)});$("body").append(this._mirror);this._input.on("keydown keyup input propertychange change",function(){r.update()});(function(){r.update()})()}return n.prototype.getOptions=function(){return this._options},n.prototype.update=function(){var n=this._input.val()||"",t;n!==this._mirror.text()&&(this._mirror.text(n),t=this._mirror.width()+this._options.space,this._input.width(t))},n.getDefaultOptions=function(){return this._defaultOptions},n.getInstanceKey=function(){return"autosizeInputInstance"},n._defaultOptions=new t,n}();n.AutosizeInput=i,function(t){var i="autosize-input",r=["text","password","search","url","tel","email","number"];t.fn.autosizeInput=function(u){return this.each(function(){if(this.tagName=="INPUT"&&t.inArray(this.type,r)>-1){var f=t(this);f.data(n.AutosizeInput.getInstanceKey())||(u==undefined&&(u=f.data(i)),f.data(n.AutosizeInput.getInstanceKey(),new n.AutosizeInput(this,u)))}})};t(function(){t("input[data-"+i+"]").autosizeInput()})}(jQuery)})(Plugins||(Plugins={}))
