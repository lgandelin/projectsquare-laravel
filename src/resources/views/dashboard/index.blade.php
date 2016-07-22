@extends('projectsquare::default')

@section('content')
    @if (!$is_client)
        <!--<ol class="breadcrumb">
            <li class="active">{{ trans('projectsquare::dashboard.panel_title') }}</li>
        </ol>-->
    @else
        @include('projectsquare::includes.project_bar', ['active' => 'dashboard', 'project' => $current_project])
    @endif

    <div class="dashboard-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 total-width" style="display: none;"></div>

            @foreach ($widgets as $widget)
                <div class="col-lg-{{ $widget->width }} col-md-12 widget" id="{{ $widget->name }}-widget" data-w="{{ $widget->width }}">
                    @include('projectsquare::dashboard.blocks.' . $widget->name)
                </div>
            @endforeach

            {{--<div class="col-lg-12 col-md-12 widget" id="tasks-widget" data-w="12">
                @include('projectsquare::dashboard.blocks.tasks')
            </div>

            <div class="col-lg-7 col-md-12 widget" id="tickets-widget" data-w="7">
                @include('projectsquare::dashboard.blocks.tickets')
            </div>

            <div class="col-lg-5 col-md-12 widget" id="messages-widget" data-w="5">
                @include('projectsquare::dashboard.blocks.messages')
            </div>

            @if (!$is_client)
                <div class="col-lg-12 col-md-12 widget" id="planning-widget" data-w="12">
                    @include('projectsquare::dashboard.blocks.planning')
                </div>
            -@endif--}}
        </div>
    </div>

    @include('projectsquare::dashboard.new-message')
    @include('projectsquare::dashboard.create-conversation-modal')
    @include('projectsquare::templates.new-todo')
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>
    <script>
        var defaultDate = "{{ date('Y-m-d') }}";
        var events = [
            @foreach ($events as $event)
            {
                id: {{ $event->id }},
                title: "{{ $event->name }}",
                start: "{{ $event->startTime->format(DATE_ISO8601) }}",
                end: "{{ $event->endTime->format(DATE_ISO8601) }}",
                color: "{{ isset($event->color) ? $event->color : null }}",
                project_id: "{{ isset($event->project_id) ? $event->project_id : null }}",
                url: "{{ isset($event->ticketID) ? route('tickets_edit', ['id' => $event->ticketID]) : null }}"
            },
            @endforeach
        ];
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        $('.widget .block').resizable({
            resize: function( event, ui ) {
                var col = Math.round((ui.size.width / $('.dashboard-content .row').width()) * 12);
                ui.element.removeAttr('style');
                ui.element.closest('.widget').removeClass(classMatchHandler(/^col-lg-/)).addClass('col-lg-' + col);
                ui.element.closest('.widget').attr('data-w', col)
            },
            stop: function( event, ui ) {
                updateWidgets();
            },
            handles: 'e'
        });

        $('.dashboard-content .row').sortable({
            cursor: "move",
            tolerance: "intersect",
            placeholder: "ui-state-highlight",
            handle: ".move-widget",
            helper: "clone",
            items: ".widget:not(.total-width)",
            sort: function(event, ui) {
                var col = Math.round((ui.item.width() / $('.dashboard-content .total-width').width()) * 12);
                ui.placeholder.addClass('col-lg-' + col);
                ui.placeholder.height(ui.item.height()-30);
            },
            stop: function ( event, ui ) {
                updateWidgets();
            }
        });

        function classMatchHandler(regex) {
            return function (index, classes) {
                return classes.split(/\s+/).filter(function (el) {return regex.test(el);}).join(' ');
            }
        }

        function updateWidgets() {
            var widgetIDs = $( ".dashboard-content .row" ).sortable( "toArray" );
            widgets = []
            for (var i in widgetIDs) {
                var widgetID = widgetIDs[i];
                var width = $('#' + widgetID).attr('data-w');
                widgets.push({name: widgetID.replace('-widget', ''), width: width});
            }
            var string = JSON.stringify(widgets);
            if (string != "") {
                createCookie('dashboard-widgets', JSON.stringify(widgets));
            }
        }

    </script>
@endsection