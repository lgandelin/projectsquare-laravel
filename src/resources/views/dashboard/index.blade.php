@extends('projectsquare::default')

@section('content')
    @if (!$is_client)
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

        </div>
    </div>

    <input type="hidden" id="current-user-id" value="{{ $logged_in_user->id }}" />

    @include('projectsquare::dashboard.new-message')
    @include('projectsquare::dashboard.create-conversation-modal')
    @include('projectsquare::templates.new-todo')
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/locale-all.js') }}"></script>
    <script>
        var defaultDate = "{{ date('Y-m-d') }}";
        var events = [
            @foreach ($events as $event)
            {
                id: "{{ $event->id }}",
                title: "{{ $event->name }}",
                start: "{{ $event->startTime->format(DATE_ISO8601) }}",
                end: "{{ $event->endTime->format(DATE_ISO8601) }}",
                color: "{{ isset($event->color) ? $event->color : null }}",
                project_id: "{{ isset($event->project_id) ? $event->project_id : null }}",
                @if (isset($event->ticketID) && $event->ticketID > 0)url: "{{ route('tickets_edit', ['id' => $event->ticketID]) }}",@endif
                @if (isset($event->taskID) && $event->taskID > 0)url: "{{ route('tasks_edit', ['id' => $event->taskID]) }}",@endif
            },
            @endforeach
        ];
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection