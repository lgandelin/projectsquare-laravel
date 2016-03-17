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
                    <input type="text" class="form-control start_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                </div>

                <div class="form-group">
                    <label for="name">Date de fin</label><br/>
                    <input type="text" class="form-control end_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                    <input type="text" class="form-control start_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                </div>

                <input type="hidden" class="id" value="" />
                <input type="button" class="btn btn-success btn-valid" value="{{ trans('projectsquare::generic.valid') }}">
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

                        }
                    });
                },
                eventClick: function(event, jsEvent, view) {

                    $('#calendar .fc-event').removeClass('current-event');
                    $(this).addClass('current-event');

                    //Get infos from ajax

                    $('#event-infos').find('.id').val(event._id);
                    $('#event-infos').find('.name').val(event.title);
                    $('#event-infos').find('.start_time').val(event.start.format());
                    $('#event-infos').find('.end_time').val(event.end.format());
                    $('#event-infos').show();
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
                        }
                    });

                    $('#calendar').fullCalendar('unselect');
                }
            });

            //VALID UPDATE EVENT
            $('#event-infos .btn-valid').click(function() {
                var data = {
                    id: $('#event-infos .id').val(),
                    name: $('#event-infos .name').val(),
                    start_time: $('#event-infos .start_time').val(),
                    start_time_hour: $('#event-infos .start_time_hour').val(),
                    end_time: $('#event-infos .end_time').val(),
                    end_time_hour: $('#event-infos .end_time_hour').val(),
                };
            });
        });

    </script>
@endsection

@section('stylesheets')
    <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css" rel="stylesheet">
@endsection