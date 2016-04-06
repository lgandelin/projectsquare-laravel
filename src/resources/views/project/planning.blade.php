@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'planning'])

    <div class="settings-template">
        <h1 class="page-header">{{ trans('projectsquare::project.planning') }}</h1>

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
            <div id="planning" class="col-md-9"></div>
            <div id="step-infos" class="col-md-3">
                <h3>{{ trans('projectsquare::planning.informations') }}</h3>

                <div class="wrapper" style="display: none">
                    <div class="loading" style="display: none"></div>

                    <div class="form-group">
                        <label for="name">{{ trans('projectsquare::planning.name') }}</label>
                        <input type="text" class="form-control name" placeholder="{{ trans('projectsquare::planning.name') }}" value="" />
                    </div>

                    <div class="form-group">
                        <label for="name">{{ trans('projectsquare::planning.start_time') }}</label><br/>
                        <input type="text" class="form-control start_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                        <input type="time" class="form-control start_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                    </div>

                    <div class="form-group">
                        <label for="name">{{ trans('projectsquare::planning.end_time') }}</label><br/>
                        <input type="text" class="form-control end_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                        <input type="time" class="form-control end_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                    </div>

                    <input type="hidden" class="id" value="" />
                    <input type="button" class="btn btn-success btn-valid" value="{{ trans('projectsquare::generic.valid') }}">
                    <input type="button" class="btn btn-default btn-close" value="{{ trans('projectsquare::generic.close') }}">
                </div>
            </div>

            <input type="hidden" id="project_id" value="{{ $project->id }}" />
        </div>
        @endsection

        @section('scripts')
            <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
            <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
            <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
            <script src="{{ asset('js/planning.js') }}"></script>
            <script>
                var defaultDate = "{{ date('Y-m-d') }}";
                var steps = [
                        @foreach ($steps as $step)
                            {
                        id: {{ $step->id }},
                        title: "{{ $step->name }}",
                        start: "{{ $step->startTime->format(DATE_ISO8601) }}",
                        end: "{{ $step->endTime->format(DATE_ISO8601) }}",
                        project_id: "{{ isset($step->project_id) ? $step->project_id : null }}",
                    },
                    @endforeach
                ];
            </script>
@endsection