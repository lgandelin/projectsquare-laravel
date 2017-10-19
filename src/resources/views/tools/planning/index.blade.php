@extends('projectsquare::default')

@section('content')
    <div class="templates planning-template">

        <div class="page-header">
            <h1>{{ __('projectsquare::planning.planning') }}
                @include('projectsquare::includes.tooltip', [
                    'text' => __('projectsquare::tooltips.planning')
              ])
            </h1>
        </div>

        @include('projectsquare::tools.planning.middle-column')

        <div class="content-page">
            <form method="get">
                <div class="row">

                    <h2>{{ __('projectsquare::planning.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="">{{ __('projectsquare::tickets.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_user" id="filter_user">
                            <option value="">{{ __('projectsquare::tickets.filters.by_allocated_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($filters['user'] == $user->id || (!$filters['user'] && $user->id == $currentUserID))selected="selected" @endif>{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <hr/>

            <div class="row">
                <div id="planning" class="col-lg-9 col-md-12"></div>
                <div class="col-lg-3 col-md-12 col-xm-12 col-xs-12">
                    <div id="event-infos">
                        <h3>{{ __('projectsquare::planning.informations') }}</h3>
                        <form method="get">
                            <div class="wrapper" style="display: none">
                                <div class="loading" style="display: none"></div>

                                <div class="form-group">
                                    <label for="name">{{ __('projectsquare::planning.name') }}</label>
                                    <input type="text" class="form-control name" placeholder="{{ __('projectsquare::planning.name') }}" value="" />
                                </div>

                                <div class="form-group">
                                    <label for="name">{{ __('projectsquare::planning.start_time') }}</label><br/>
                                    <input type="text" class="form-control start_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                                    <input type="time" class="form-control start_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                                </div>

                                <div class="form-group">
                                    <label for="name">{{ __('projectsquare::planning.end_time') }}</label><br/>
                                    <input type="text" class="form-control end_time datepicker" placeholder="dd/mm/YYYY" value="" style="width: 200px; display: inline-block" />
                                    <input type="time" class="form-control end_time_hour" placeholder="hh:mm" style="width: 100px; display: inline-block;"/>
                                </div>

                                <div class="form-group">
                                    <label for="project_id">{{ __('projectsquare::planning.project') }}</label><br/>
                                    <select name="project_id" class="form-control project_id">
                                        <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" class="id" value="" />
                                <input type="button" class="btn valid btn-valid" value="{{ __('projectsquare::generic.valid') }}">
                                <input type="button" class="btn btn-close button" value="{{ __('projectsquare::generic.close') }}">
                            </div>
                        </form>
                    </div>
                </div>

                <input type="hidden" id="user_id" value="{{ $userID }}" />
            </div>
        </div>
    </div>

    <input type="hidden" class="tickets-current-project" />
    <input type="hidden" class="tickets-current-ticket" />

    <input type="hidden" class="tasks-current-project" />
    <input type="hidden" class="tasks-current-task" />
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/locale-all.js') }}"></script>
    <script src="{{ asset('js/planning.js') }}"></script>
    <script>
        var defaultDate = "{{ date('Y-m-d') }}";
        var events = [
            @foreach ($events as $event)
                {
                    id: "{{ $event->id }}",
                    title: "{!! $event->name !!}",
                    start: "{{ $event->startTime->format(DATE_ISO8601) }}",
                    end: "{{ $event->endTime->format(DATE_ISO8601) }}",
                    color: "{{ isset($event->color) ? $event->color : null }}",
                    project_id: "{{ isset($event->project_id) ? $event->project_id : null }}",
                    project_name: "{{ isset($event->projectName) ? $event->projectName : null }}",
                },
            @endforeach
        ];
    </script>
@endsection