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
            <div class="col-lg-12 col-md-12 widget">
                @include('projectsquare::dashboard.blocks.tasks')
            </div>

            <div class="col-lg-7 col-md-12 widget">
                @include('projectsquare::dashboard.blocks.tickets')
            </div>

            <div class="col-lg-5 col-md-12 widget">
                @include('projectsquare::dashboard.blocks.messages')
            </div>

            @if (!$is_client)
                <div class="col-lg-12 col-md-12 widget">
                    @include('projectsquare::dashboard.blocks.planning')
                </div>
            @endif
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
                ui.element.closest('.widget').removeClass(makeRemoveClassHandler(/^col-lg-/)).addClass('col-lg-' + col);
            },
            handles: 'e'
        });

        $('.dashboard-content .row').sortable({
            cursor: "move",
            tolerance: "pointer",
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            handle: ".move-widget",
            helper: "clone",
            sort: function(event, ui) {
                var col = Math.round((ui.item.width() / $('.dashboard-content .row').width()) * 12);
                ui.placeholder.addClass('col-lg-' + col)
                ui.placeholder.height(ui.item.height() - 34)
            },
        });

        function makeRemoveClassHandler(regex) {
            return function (index, classes) {
                return classes.split(/\s+/).filter(function (el) {return regex.test(el);}).join(' ');
            }
        }

    </script>
@endsection