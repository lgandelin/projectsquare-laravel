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
                defaultDate: '2016-03-12',
                eventColor: '#378006',
                editable: true,
                lang: 'fr',
                aspectRatio: 2,
                weekNumbers: true,
                weekends: false,
                minTime: '07:00',
                maxTime: '21:00',
                events: [
                    {
                        title: 'All Day Event',
                        start: '2016-03-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2016-03-07',
                        end: '2016-03-10',
                        backgroundColor: '#ff0000',
                        borderColor: '#ff0000'
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: '2016-03-09T16:00:00'
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: '2016-03-16T16:00:00'
                    },
                    {
                        title: 'Conference',
                        start: '2016-03-11',
                        end: '2016-03-13',
                        backgroundColor: '#ff0000',
                        borderColor: '#ff0000'
                    },
                    {
                        title: 'Meeting',
                        start: '2016-03-12T10:30:00',
                        end: '2016-03-12T12:30:00'
                    },
                    {
                        title: 'Lunch',
                        start: '2016-03-12T12:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2016-03-12T14:30:00'
                    },
                    {
                        title: 'Happy Hour',
                        start: '2016-03-12T17:30:00'
                    },
                    {
                        title: 'Dinner',
                        start: '2016-03-12T20:00:00'
                    },
                    {
                        title: 'Birthday Party',
                        start: '2016-03-13T07:00:00'
                    },
                    {
                        title: 'Click for Google',
                        url: 'http://google.com/',
                        start: '2016-03-28'
                    }
                ],
                eventRender: function(event, element) {
                    element.append( "<span class='delete'>X</span>" );
                    element.find(".delete").click(function() {
                        console.log(event.title)
                        $('#calendar').fullCalendar('removeEvents',event._id);
                    });
                },
                eventDrop: function(event, delta, revertFunc) {
                    console.log(event.title);
                    console.log(event.start.format());
                },
            });

        });

    </script>

@endsection

@section('stylesheets')
    <link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css" rel="stylesheet">
@endsection