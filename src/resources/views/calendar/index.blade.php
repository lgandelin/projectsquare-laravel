@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Calendrier</li>
    </ol>

    <div class="page-header">
        <h1>Calendrier</h1>

        <div class="row">
            <div id="calendar" class="col-md-9"></div>
            <div id="event-infos" class="col-md-3" style="display: none;">
                <h3>Informations</h3>

                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control name" placeholder="Nom de l'évenement" value="" />
                </div>

                <div class="form-group">
                    <label for="name">Date de début</label><br/>
                    <input type="text" class="form-control start_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                    <input type="time" class="form-control start_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                </div>

                <div class="form-group">
                    <label for="name">Date de fin</label><br/>
                    <input type="text" class="form-control end_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                    <input type="time" class="form-control end_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                </div>

                <input type="hidden" class="id" value="" />
                <input type="button" class="btn btn-success btn-valid" value="{{ trans('projectsquare::generic.valid') }}">
                <input type="button" class="btn btn-default btn-close" value="{{ trans('projectsquare::generic.close') }}">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>
    <script src="{{ asset('js/calendar.js') }}"></script>

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
                minTime: '07:00',
                maxTime: '21:00',
                events: [
                 @foreach ($events as $event)
                    {
                        id: {{ $event->id }},
                        title: "{{ $event->name }}",
                        start: "{{ $event->startTime->format(DATE_ISO8601) }}",
                        end: "{{ $event->endTime->format(DATE_ISO8601) }}",
                    },
                 @endforeach
                ],
                eventRender: function(event, element) {
                    element.append( "<span class='delete'>X</span>" );
                    element.find(".delete").click(function() {
                        $('#event-infos').hide();
                        
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
                            $('#event-infos').find('.name').val(data.event.name);
                            $('#event-infos').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').show();
                        }
                    });
                },
                eventResize: function(event, delta, revertFunc) {

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
                            $('#event-infos').find('.name').val(data.event.name);
                            $('#event-infos').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').show();
                        }
                    });
                },
                eventClick: function(event, jsEvent, view) {

                    var data = {
                        id: event._id,
                        _token: $('#csrf_token').val()
                    };

                    $.ajax({
                        type: "POST",
                        url: route_event_get_infos,
                        data: data,
                        success: function(data) {
                            $('#event-infos').find('.id').val(data.event.id);
                            $('#event-infos').find('.name').val(data.event.name);
                            $('#event-infos').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').show();
                        }
                    });
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {

                    var temporaryID = uniqid();
                    var event = {
                        id: temporaryID,
                        title: 'Nouvel evenement',
                        start: start,
                        end: end,
                        allDay: false
                    };

                    $('#calendar').fullCalendar('renderEvent', event, true);

                    var data = {
                        name: 'Nouvel evenement',
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

                            $('#event-infos').find('.id').val(data.event.id);
                            $('#event-infos').find('.name').val(data.event.name);
                            $('#event-infos').find('.start_time').val(moment(data.event.start_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.start_time_hour').val(moment(data.event.start_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').find('.end_time').val(moment(data.event.end_time).format('DD/MM/YYYY'));
                            $('#event-infos').find('.end_time_hour').val(moment(data.event.end_time, 'YYYY-MM-DD HH:mm').format('HH:mm'));
                            $('#event-infos').show();
                        }
                    });

                    $('#calendar').fullCalendar('unselect');
                }
            });

            //VALID UPDATE EVENT
            $('#event-infos .btn-valid').click(function() {
                var data = {
                    event_id: $('#event-infos .id').val(),
                    name: $('#event-infos .name').val(),
                    start_time: moment($('#event-infos .start_time').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') + ' ' + $('#event-infos .start_time_hour').val() + ':00',
                    end_time: moment($('#event-infos .end_time').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') + ' ' + $('#event-infos .end_time_hour').val() + ':00',
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

                        $('#calendar').fullCalendar('updateEvent', event);
                    }
                });
            });

            //CLOSE EVENT INFOS
            $('#event-infos .btn-close').click(function() {
                $(this).parent().hide();
            });
        });

    </script>
@endsection

@section('stylesheets')
    <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css" rel="stylesheet">
@endsection