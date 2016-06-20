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
            <div class="col-lg-7 col-md-12">
                @include('projectsquare::dashboard.blocks.tickets')
            </div>

            <div class="col-lg-5 col-md-12">
                @include('projectsquare::dashboard.blocks.messages')
            </div>
        </div>

        @if (!$is_client)
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    @include('projectsquare::dashboard.blocks.planning')
                </div>

                <!--<div class="col-lg-5 col-md-12">
                    @include('projectsquare::dashboard.blocks.monitoring')
                     <!--@include('projectsquare::dashboard.blocks.tasks')
                </div>-->
            </div>
        @endif
    </div>

    @include('projectsquare::dashboard.new-message')
    @include('projectsquare::dashboard.create-conversation-modal')
    @include('projectsquare::templates.new-task')
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>
    <script src="{{ asset('js/tasks.js') }}"></script>
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
@endsection