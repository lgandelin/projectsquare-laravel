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
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>

    <script>

        $(document).ready(function() {

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultDate: "{{ date('Y-m-d') }}",
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
                }
            });

        });

    </script>

@endsection

@section('stylesheets')
    <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css" rel="stylesheet">
@endsection