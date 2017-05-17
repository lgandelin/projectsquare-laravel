@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates planning-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::planning.planning') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.planning')
                  ])
                </h1>
            </div>

            <form method="get">
                <div class="row">

                    <h2>{{ trans('projectsquare::planning.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_user" id="filter_user">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_allocated_user') }}</option>
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
                        <h3>{{ trans('projectsquare::planning.informations') }}</h3>
                        <form method="get">
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

                                <div class="form-group">
                                    <label for="project_id">{{ trans('projectsquare::planning.project') }}</label><br/>
                                    <select name="project_id" class="form-control project_id">
                                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" class="id" value="" />
                                <input type="button" class="btn valid btn-valid" value="{{ trans('projectsquare::generic.valid') }}">
                                <input type="button" class="btn btn-close button" value="{{ trans('projectsquare::generic.close') }}">
                            </div>
                        </form>
                    </div>
                </div>

                <input type="hidden" id="user_id" value="{{ $userID }}" />
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
                    title: "@if (isset($event->projectClient))[{!! $event->projectClient !!}]@endif @if (isset($event->projectName)){!! $event->projectName !!}\n @endif {!! $event->name !!}",
                    start: "{{ $event->startTime->format(DATE_ISO8601) }}",
                    end: "{{ $event->endTime->format(DATE_ISO8601) }}",
                    color: "{{ isset($event->color) ? $event->color : null }}",
                    project_id: "{{ isset($event->project_id) ? $event->project_id : null }}",
                },
            @endforeach
        ];
    </script>
@endsection