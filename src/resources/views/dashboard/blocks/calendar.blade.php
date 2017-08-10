<div class="block calendar">
    <h3>{{ trans('projectsquare::project.calendar') }}
        @include('projectsquare::includes.tooltip', [
            'text' => trans('projectsquare::tooltips.calendar_title')
        ])
        <a href="{{ route('project_calendar',['id' => $current_project->id]) }}" class="all pull-right" title="{{ trans('projectsquare::dashboard.see_calendar') }}"></a>
        <a href="#" class="glyphicon glyphicon-move move-widget pull-right" title="{{ trans('projectsquare::dashboard.move_widget') }}"></a>
    </h3>
    <div class="block-content ">
        <div id="calendar"></div>
     
    </div>
</div>

@section('scripts')
    @parent
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/locale-all.js') }}"></script>
    <script>
        var defaultDate = "{{ date('Y-m-d') }}";
        var steps = [
            @foreach ($steps as $step)
            {
                id: "{{ $step->id }}",
                title: "{!! $step->name !!}",
                start: "{{ $step->startTime->format(DATE_ISO8601) }}",
                end: "{{ $step->endTime->format(DATE_ISO8601) }}",
                project_id: "{{ isset($step->project_id) ? $step->project_id : null }}",
                color: "{{ isset($step->color) ? $step->color : null }}",
                allDay: true
            },
            @endforeach
        ];
        var is_client = @if ($is_client) {{ $is_client}} @else 0 @endif;
    </script>
    <script src="{{ asset('js/calendar.js') }}"></script>
@endsection