@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'calendar'])
    <div class="content-page">
        <div class="templates settings-template">
            <h1 class="page-header">{{ __('projectsquare::project.calendar') }}
                @include('projectsquare::includes.tooltip', [
                    'text' => __('projectsquare::tooltips.calendar_title')
                ])
            </h1>

            @if (isset($error))
                <div class="info bg-danger">
                    {{ $error }}
                </div>
            @endif

            @if (isset($confirmation))
                <div class="info bg-success">
                    {{ $confirmation }}
                </div>
            @endif

            <div class="row">
                <div id="calendar" class="col-md-9"></div>
                <div id="step-infos" class="col-md-3">
                    <h3>{{ __('projectsquare::calendar.informations') }}</h3>

                    <div class="wrapper" style="display: none">
                        <div class="loading" style="display: none"></div>

                        <div class="form-group">
                            <label for="name">{{ __('projectsquare::calendar.name') }}</label>
                            <input type="text" class="form-control name" placeholder="{{ __('projectsquare::calendar.name') }}" value="" />
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('projectsquare::calendar.start_time') }}</label><br/>
                            <input type="text" class="form-control start_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('projectsquare::calendar.end_time') }}</label><br/>
                            <input type="text" class="form-control end_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('projectsquare::calendar.color') }}</label>
                            <input type="text" name="color" class="form-control colorpicker" data-control="saturation" placeholder="{{ __('projectsquare::calendar.color') }}" value="" size="7">
                        </div>

                        <input type="hidden" class="id" value="" />
                        <button class="btn valid btn-valid"><i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}</button>
                        <button class="btn back btn-close"><span class="glyphicon glyphicon-arrow-left"></span> {{ __('projectsquare::generic.close') }}</button>
                    </div>
                </div>

                <input type="hidden" id="project_id" value="{{ $project->id }}" />
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
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
    <script>
        $('.colorpicker').minicolors({
            theme: 'bootstrap',
        });
    </script>
@endsection