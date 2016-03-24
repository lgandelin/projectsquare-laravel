@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::calendar.calendar') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::calendar.calendar') }}</h1>

        <div class="row">
            <div id="calendar" class="col-md-9"></div>
            <div id="event-infos" class="col-md-3">
                <h3>{{ trans('projectsquare::calendar.informations') }}</h3>

                <div class="wrapper" style="display: none">
                    <div class="loading" style="display: none"></div>

                    <div class="form-group">
                        <label for="name">{{ trans('projectsquare::calendar.name') }}</label>
                        <input type="text" class="form-control name" placeholder="{{ trans('projectsquare::calendar.name') }}" value="" />
                    </div>

                    <div class="form-group">
                        <label for="name">{{ trans('projectsquare::calendar.start_time') }}</label><br/>
                        <input type="text" class="form-control start_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                        <input type="time" class="form-control start_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                    </div>

                    <div class="form-group">
                        <label for="name">{{ trans('projectsquare::calendar.end_time') }}</label><br/>
                        <input type="text" class="form-control end_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                        <input type="time" class="form-control end_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                    </div>

                    <div class="form-group">
                        <label for="project_id">{{ trans('projectsquare::calendar.project') }}</label><br/>
                        <select name="project_id" class="form-control project_id">
                            <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->client->name }} - {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" class="id" value="" />
                    <input type="button" class="btn btn-success btn-valid" value="{{ trans('projectsquare::generic.valid') }}">
                    <input type="button" class="btn btn-default btn-close" value="{{ trans('projectsquare::generic.close') }}">
                </div>
            </div>

            <div id="tickets-list" class="col-md-3" style="display: none; margin-top: 5rem;">
                <h3>{{ trans('projectsquare::calendar.tickets_list') }}</h3>
                @foreach ($tickets as $ticket)
                    <div id="ticket-{{ $ticket->id }}" data-project="{{ $ticket->project->id }}" data-ticket="{{ $ticket->id }}" data-color="{{ $ticket->project->color }}" data-event='{"title":"#{{ $ticket->id }} - {{ $ticket->title }}"}' data-duration="01:00" class="ticket fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="background: {{ $ticket->project->color }}; margin-bottom: 1rem; width: 50%; border: none !important;">
                        <div class="fc-content"><div class="fc-title">#{{ $ticket->id }} - {{ $ticket->title }}</div></div>
                    </div>
                @endforeach
            </div>

            <input type="hidden" class="tickets-current-project" />
            <input type="hidden" class="tickets-current-ticket" />
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <script>

        $(document).ready(function() {

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultDate: "{{ date('Y-m-d') }}",
                defaultView: "agendaWeek",
                editable: true,
                lang: 'fr',
                aspectRatio: 2,
                weekNumbers: true,
                weekends: false,
                minTime: '08:00',
                maxTime: '21:00',
                droppable: true,
                events: [
                @foreach ($events as $event)
                    {
                        id: {{ $event->id }},
                        title: "{{ $event->name }}",
                        start: "{{ $event->startTime->format(DATE_ISO8601) }}",
                        end: "{{ $event->endTime->format(DATE_ISO8601) }}",
                        color: "{{ isset($event->color) ? $event->color : null }}",
                        project_id: "{{ isset($event->project_id) ? $event->project_id : null }}",
                    },
                @endforeach
                ],
                eventRender: function(event, element) {
                    element.append( "<span class='delete'>X</span>" );
                    element.find(".delete").click(function() {
                        $('#event-infos .wrapper').hide();

                        var data = {
                            event_id: event._id,
                            _token: $('#csrf_token').val()
                        };

                        $.ajax({
                            type: "POST",
                            url: route_event_delete,
                            data: data,
                            success: function(data) {
                                $('#calendar').fullCalendar('removeEvents', event._id);
                                $('#event-infos .wrapper').hide();
                            }
                        });
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
                        type: "POST",
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
                        title: "{{ trans('projectsquare::calendar.new_event') }}",
                        start: start,
                        end: end,
                        allDay: false
                    };

                    $('#calendar').fullCalendar('renderEvent', event, true);

                    var data = {
                        name: "{{ trans('projectsquare::calendar.new_event') }}",
                        start_time: start.format(),
                        end_time: end.format(),
                        _token: $('#csrf_token').val()
                    };

                    $.ajax({
                        type: "POST",
                        url: route_event_create,
                        data: data,
                        success: function(data) {
                            var events = $('#calendar').fullCalendar( 'clientEvents', temporaryID);
                            var event = events[0];
                            event._id = data.event.id;
                            $('#calendar').fullCalendar('updateEvent', event);

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

                    $('#calendar').fullCalendar('unselect');
                },
                drop: function(date, jsEvent, ui, resourceId) {
                    $('.tickets-current-project').val($(this).data('project'));
                    $('.tickets-current-ticket').val($(this).data('ticket'));

                    $(this).hide();
                },
                eventReceive: function(event, delta, revertFunc) {
                    $('#event-infos .wrapper').show();
                    $('#event-infos .loading').show();

                    var data = {
                        event_id: event.id,
                        name: event.title,
                        start_time: event.start.format(),
                        end_time: event.end.format(),
                        project_id: $('.tickets-current-project').val(),
                        ticket_id: $('.tickets-current-ticket').val(),
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
                            $('#calendar').fullCalendar('updateEvent', event);

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

                        var events = $('#calendar').fullCalendar( 'clientEvents', data.event.id);
                        var event = events[0];
                        event.title = data.event.name;
                        event.start = data.event.start_time;
                        event.end = data.event.end_time;
                        event.color = data.event.color;

                        $('#calendar').fullCalendar('updateEvent', event);
                        $('#event-infos .loading').hide();
                    }
                });
            });

            //CLOSE EVENT INFOS
            $('#event-infos .btn-close').click(function() {
                $(this).parent().hide();
            });

            //DRAG AND DROP TICKETS
            $('#tickets-list .ticket').each(function() {

                // store data so the calendar knows to render an event upon drop
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

            $('#tickets-list').show();
        });

    </script>
@endsection