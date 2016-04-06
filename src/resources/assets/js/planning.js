
$(document).ready(function() {

    $('#planning').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
        },
        defaultDate: defaultDate,
        defaultView: "month",
        editable: true,
        lang: 'fr',
        aspectRatio: 2,
        weekNumbers: true,
        weekends: false,
        minTime: '08:00',
        maxTime: '21:00',
        droppable: true,
        steps: steps,
        stepRender: function(step, element) {
            element.append( "<span class='delete'>X</span>" );
            element.find(".delete").click(function() {
                $('#step-infos .wrapper').hide();

                var data = {
                    step_id: step._id,
                    _token: $('#csrf_token').val()
                };

                $.ajax({
                    type: "POST",
                    url: route_step_delete,
                    data: data,
                    success: function(data) {
                        $('#planning').fullCalendar('removeEvents', step._id);
                        $('#step-infos .wrapper').hide();
                    }
                });
            });
        },
        stepDrop: function(step, delta, revertFunc) {

            var data = {
                step_id: step._id,
                start_time: step.start.format(),
                end_time: step.end.format(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_step_update,
                data: data,
                success: function(data) {
                    if ($('#step-infos .wrapper').is(':visible') && data.step.projectID == $('#step-infos .wrapper').find('.project_id').val()) {
                        $('#step-infos .wrapper').find('.start_time').val(moment(data.step.start_time).format('DD/MM/YYYY'));
                        $('#step-infos .wrapper').find('.start_time_hour').val(moment(data.step.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                        $('#step-infos .wrapper').find('.end_time').val(moment(data.step.end_time).format('DD/MM/YYYY'));
                        $('#step-infos .wrapper').find('.end_time_hour').val(moment(data.step.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    }
                }
            });
        },
        stepResize: function(step, delta, revertFunc) {
            $('#step-infos .wrapper').show();
            $('#step-infos .loading').show();

            var data = {
                step_id: step._id,
                start_time: step.start.format(),
                end_time: step.end.format(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_step_update,
                data: data,
                success: function(data) {
                    if ($('#step-infos .wrapper').is(':visible') && data.step.projectID == $('#step-infos .wrapper').find('.project_id').val()) {
                        $('#step-infos .wrapper').find('.name').val(data.step.name);
                        $('#step-infos .wrapper').find('.start_time').val(moment(data.step.start_time).format('DD/MM/YYYY'));
                        $('#step-infos .wrapper').find('.start_time_hour').val(moment(data.step.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                        $('#step-infos .wrapper').find('.end_time').val(moment(data.step.end_time).format('DD/MM/YYYY'));
                        $('#step-infos .wrapper').find('.end_time_hour').val(moment(data.step.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    }
                    $('#step-infos .loading').hide();
                }
            });
        },
        stepClick: function(step, jsEvent, view) {
            $('#step-infos .wrapper').show();
            $('#step-infos .loading').show();
            var data = {
                id: step._id,
                _token: $('#csrf_token').val()
            };
            $.ajax({
                type: "GET",
                url: route_step_get_infos,
                data: data,
                success: function(data) {
                    $('#step-infos .wrapper').find('.id').val(data.step.id);
                    $('#step-infos .wrapper').find('.name').val(data.step.name);
                    $('#step-infos .wrapper').find('.start_time').val(moment(data.step.start_time).format('DD/MM/YYYY'));
                    $('#step-infos .wrapper').find('.start_time_hour').val(moment(data.step.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#step-infos .wrapper').find('.end_time').val(moment(data.step.end_time).format('DD/MM/YYYY'));
                    $('#step-infos .wrapper').find('.end_time_hour').val(moment(data.step.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                    $('#step-infos .wrapper').find('.project_id').val(data.step.project_id);
                    $('#step-infos .wrapper').show();
                    $('#step-infos .loading').hide();
                }
            });
        },
        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
            $('#step-infos .wrapper').show();
            $('#step-infos .loading').show();

            var temporaryID = uniqid();
            var step = {
                id: temporaryID,
                title: "Nouvel evenement",
                start: start,
                end: end,
                allDay: false
            };

            $('#planning').fullCalendar('renderEvent', step, true);

            var data = {
                name: "Nouvel evenement",
                start_time: start.format(),
                end_time: end.format(),
                user_id: $('#user_id').val(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "POST",
                url: route_step_create,
                data: data,
                success: function(data) {
                    var steps = $('#planning').fullCalendar( 'clientEvents', temporaryID);
                    var step = steps[0];
                    step._id = data.step.id;
                    $('#planning').fullCalendar('updateEvent', step);

                    $('#step-infos .wrapper').find('.id').val(data.step.id);
                    $('#step-infos .wrapper').find('.name').val('');
                    $('#step-infos .wrapper').find('.start_time').val(moment(data.step.start_time).format('DD/MM/YYYY'));
                    $('#step-infos .wrapper').find('.end_time').val(moment(data.step.end_time).format('DD/MM/YYYY'));
                    $('#step-infos .wrapper').find('.project_id').val(data.step.project_id);
                    $('#step-infos .wrapper').show();

                    $('#step-infos .wrapper').find('.name').focus();
                    $('#step-infos .loading').hide();
                }
            });

            $('#planning').fullCalendar('unselect');
        },
        drop: function(date, jsEvent, ui, resourceId) {
        },
    });

    //VALID UPDATE STEP
    $('#step-infos .btn-valid').click(function() {
        $('#step-infos .loading').show();
        var data = {
            step_id: $('#step-infos .id').val(),
            name: $('#step-infos .name').val(),
            start_time: moment($('#step-infos .start_time').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
            end_time: moment($('#step-infos .end_time').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
            project_id: $('#step-infos .project_id').val(),
            _token: $('#csrf_token').val()
        };

        $.ajax({
            type: "POST",
            url: route_step_update,
            data: data,
            success: function(data) {

                var steps = $('#planning').fullCalendar( 'clientEvents', data.step.id);
                var step = steps[0];
                step.title = data.step.name;
                step.start = data.step.start_time;
                step.end = data.step.end_time;

                $('#planning').fullCalendar('updateEvent', step);
                $('#step-infos .loading').hide();
            }
        });
    });

    //CLOSE STEP INFOS
    $('#step-infos .btn-close').click(function() {
        $(this).parent().hide();
    });
});