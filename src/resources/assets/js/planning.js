$(document).ready(function() {

    $('#planning').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: defaultDate,
        defaultView: "agendaWeek",
        editable: true,
        locale: 'fr',
        aspectRatio: 2,
        weekNumbers: true,
        weekends: false,
        minTime: '09:00',
        maxTime: '19:00',
        /*businessHours: {
            start: '09:00',
            end: '17:00',
        },*/
        droppable: true,
        events: events,
        allDaySlot: false,
        contentHeight: 'auto',

        eventRender: function(event, element) {
            element.append('<span class="delete-event glyphicon glyphicon-remove btn-delete"></span>');
            element.find(".delete-event").click(function() {

                if (confirm('Etes-vous sûrs de vouloir supprimer cet élément ?')) {
                    $('#event-infos .wrapper').hide();

                    var data = {
                        event_id: event._id,
                        _token: $('#csrf_token').val()
                    };

                    $.ajax({
                        type: "POST",
                        url: route_event_delete,
                        data: data,
                        success: function (data) {
                            $('#planning').fullCalendar('removeEvents', event._id);
                            $('#event-infos .wrapper').hide();
                        }
                    });
                }
            });
        },
        eventDrop: function(event, delta, revertFunc) {

            var data = {
                event_id: event._id,
                start_time: event.start.format(),
                end_time: event.end.format(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_event_update,
                data: data,
                success: function(data) {
                    if ($('#event-infos .wrapper').is(':visible') && data.event.projectID == $('#event-infos .wrapper').find('.project_id').val()) {
                        $('#event-infos .wrapper').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                        $('#event-infos .wrapper').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                        $('#event-infos .wrapper').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                        $('#event-infos .wrapper').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    }
                },
                error: function(data) {
                    status = data.status
                    data = $.parseJSON(data.responseText);
                    revertFunc();
                    if (status == 301) {
                        alert(data.error);
                    } else {
                        alert('Une erreur est survenue. Veuillez nous excuser pour la gêne occasionnée');
                    }
                }
            });
        },
        eventResize: function(event, delta, revertFunc) {
            $('#event-infos .wrapper').show();
            $('#event-infos .loading').show();

            var data = {
                event_id: event._id,
                start_time: event.start.format(),
                end_time: event.end.format(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_event_update,
                data: data,
                success: function(data) {
                    if ($('#event-infos .wrapper').is(':visible') && data.event.projectID == $('#event-infos .wrapper').find('.project_id').val()) {
                        $('#event-infos .wrapper').find('.name').val(data.event.name);
                        $('#event-infos .wrapper').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                        $('#event-infos .wrapper').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                        $('#event-infos .wrapper').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                        $('#event-infos .wrapper').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    }
                    $('#event-infos .loading').hide();
                }
            });
        },
        eventClick: function(event, jsEvent, view) {
            $('#event-infos .wrapper').show();
            $('#event-infos .loading').show();
            var data = {
                id: event._id,
                _token: $('#csrf_token').val()
            };
            $.ajax({
                type: "GET",
                url: route_event_get_infos,
                data: data,
                success: function(data) {
                    $('#event-infos .wrapper').find('.id').val(data.event.id);
                    $('#event-infos .wrapper').find('.name').val(data.event.name);
                    $('#event-infos .wrapper').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                    $('#event-infos .wrapper').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#event-infos .wrapper').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                    $('#event-infos .wrapper').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#event-infos .wrapper').find('.project_id').val(data.event.project_id);
                    $('#event-infos .wrapper').show();
                    $('#event-infos .loading').hide();
                }
            });
        },
        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
            $('#event-infos .wrapper').show();
            $('#event-infos .loading').show();

            var temporaryID = uniqid();
            var event = {
                id: temporaryID,
                title: "Nouvel evenement",
                start: start,
                end: end,
                allDay: false
            };

            $('#planning').fullCalendar('renderEvent', event, true);

            var data = {
                name: "Nouvel evenement",
                start_time: start.format(),
                end_time: end.format(),
                user_id: $('#user_id').val(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_event_create,
                data: data,
                success: function(data) {
                    var events = $('#planning').fullCalendar( 'clientEvents', temporaryID);
                    var event = events[0];
                    event._id = data.event.id;
                    $('#planning').fullCalendar('updateEvent', event);

                    $('#event-infos .wrapper').find('.id').val(data.event.id);
                    $('#event-infos .wrapper').find('.name').val('');
                    $('#event-infos .wrapper').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                    $('#event-infos .wrapper').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#event-infos .wrapper').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                    $('#event-infos .wrapper').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#event-infos .wrapper').find('.project_id').val(data.event.project_id);
                    $('#event-infos .wrapper').show();

                    $('#event-infos .wrapper').find('.name').focus();
                    $('#event-infos .loading').hide();
                }
            });

            $('#planning').fullCalendar('unselect');
        },
        drop: function(date, jsEvent, ui, resourceId) {
            $('.tickets-current-project').val($(this).data('project'));
            $('.tickets-current-ticket').val($(this).data('ticket'));
            $('.tasks-current-project').val($(this).data('project'));
            $('.tasks-current-task').val($(this).data('task'));
        },
        eventReceive: function(event, delta, revertFunc) {

            if (event.allDay) {
                return false;
            }

            $('#event-infos .wrapper').show();
            $('#event-infos .loading').show();

            var data = {
                event_id: event.id,
                name: event.title,
                start_time: event.start.format(),
                end_time: event.end.format(),
                user_id: $('#user_id').val(),
                project_id: $('.tickets-current-project').val(),
                ticket_id: $('.tickets-current-ticket').val(),
                task_id: $('.tasks-current-task').val(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_event_create,
                data: data,
                success: function(data) {
                    event._id = data.event.id;
                    event.color = data.event.color;
                    event.project_id = $('.tickets-current-project').val();
                    event.ticket_id = $('.tickets-current-ticket').val();
                    event.task_id = $('.tasks-current-task').val();
                    $('#planning').fullCalendar('updateEvent', event);

                    $('#event-infos .wrapper').find('.id').val(data.event.id);
                    $('#event-infos .wrapper').find('.name').val(data.event.name);
                    $('#event-infos .wrapper').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                    $('#event-infos .wrapper').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#event-infos .wrapper').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                    $('#event-infos .wrapper').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#event-infos .wrapper').find('.project_id').val(event.project_id);

                    $('#event-infos .loading').hide();
                }
            });
        },
    });

    //VALID UPDATE EVENT
    $('#event-infos .btn-valid').click(function() {
        $('#event-infos .loading').show();
        var data = {
            event_id: $('#event-infos .id').val(),
            name: $('#event-infos .name').val(),
            start_time: moment($('#event-infos .start_time').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') + ' ' + $('#event-infos .start_time_hour').val() + ':00',
            end_time: moment($('#event-infos .end_time').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') + ' ' + $('#event-infos .end_time_hour').val() + ':00',
            project_id: $('#event-infos .project_id').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_event_update,
            data: data,
            success: function(data) {

                var events = $('#planning').fullCalendar( 'clientEvents', data.event.id);
                var event = events[0];
                event.title = data.event.name;
                event.start = data.event.start_time;
                event.end = data.event.end_time;
                event.color = data.event.color;

                $('#planning').fullCalendar('updateEvent', event);
                $('#event-infos .loading').hide();
            }
        });
    });

    //CLOSE EVENT INFOS
    $('#event-infos .btn-close').click(function() {
        $(this).parent().hide();
    });

    //DRAG AND DROP
    initTicketDragAndDrop()
    initTaskDragAndDrop()

    $('.tickets-list').show();
    $('.tasks-list').show();

    //UNALLOCATE TICKETS
    $('.tickets-list').on('click', '.unallocate-ticket', function() {
        var ticket = $(this).closest('.ticket');
        var data = {
            ticket_id: ticket.data('id'),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_ticket_unallocate,
            data: data,
            success: function(data) {
                $('#ticket-' + ticket.data('id')).find('.unallocate-ticket').hide();
                var html = $('#ticket-' + ticket.data('id'));
                $('#non-allocated-tickets-list').append(html);
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message)
            }
        });
    });

    //UNALLOCATE TASKS
    $('.tasks-list').on('click', '.unallocate-task', function() {
        var task = $(this).closest('.task');
        var data = {
            task_id: task.data('id'),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_task_unallocate,
            data: data,
            success: function(data) {
                $('#task-' + task.data('id')).find('.unallocate-task').hide();
                var html = $('#task-' + task.data('id'));
                $('#non-allocated-tasks-list').append(html);
            },
            error: function(data) {
                data = $.parseJSON(data.responseText);
                alert(data.message)
            }
        });
    });
});

function initTicketDragAndDrop() {
    $('.tickets-list .ticket').each(function() {

        // store data so the planning knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });
    });
}

function initTaskDragAndDrop() {
    $('.tasks-list .task').each(function() {

        // store data so the planning knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });
    });
}