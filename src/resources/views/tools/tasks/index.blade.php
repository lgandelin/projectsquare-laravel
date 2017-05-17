@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates tasks-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::tasks.tasks_list') }}
                     @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.tasks')
                     ])
                </h1>
            </div>

            <form method="get">
                <div class="row">

                    <h2>{{ trans('projectsquare::tasks.filters.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="">{{ trans('projectsquare::tasks.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                            <option value="">{{ trans('projectsquare::tasks.filters.by_allocated_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_status" id="filter_status">
                            <option value="">{{ trans('projectsquare::tasks.filters.by_status') }}</option>
                            @foreach ($task_statuses as $task_status)
                                <option value="{{ $task_status->id }}" @if ($filters['status'] == $task_status->id)selected="selected" @endif>{{ $task_status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <hr/>

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

            <a href="{{ route('tasks_add') }}" class="btn pull-right add create-task"></a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ trans('projectsquare::tasks.task') }}</th>
                        <th>{{ trans('projectsquare::tasks.phase') }}</th>
                        <th>{{ trans('projectsquare::tasks.client') }}</th>
                        <th>{{ trans('projectsquare::tasks.allocated_user') }}</th>
                        <th>{{ trans('projectsquare::tasks.status') }}</th>
                        <th>{{ trans('projectsquare::tasks.estimated_time') }}</th>
                        <th>{{ trans('projectsquare::tasks.spent_time') }}</th>
                        <th>{{ trans('projectsquare::generic.action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td @if (isset($task->project))style="border-left: 10px solid {{ $task->project->color }}"@endif></td>
                            <td class="entity_title"><a href="{{ route('tasks_edit', ['id' => $task->id]) }}">{{ $task->title }}</a></td>
                            <td>@if ($task->phase){{ $task->phase->name }}@endif</td>
                            <td>@if (isset($task->project) && isset($task->project->client)){{ $task->project->client->name }}@endif</td>
                            <td>
                                @if (isset($task->allocated_user))
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $task->allocated_user->id,
                                        'name' => $task->allocated_user->complete_name
                                    ])
                                @endif
                            </td>
                            <td>
                                @if ($task->status_id == 1)A faire
                                @elseif ($task->status_id == 2)En cours
                                @elseif ($task->status_id == 3)Terminé
                                @endif
                            </td>
                            <td>@if ($task->estimated_time_days > 0){{ $task->estimated_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($task->estimated_time_hours > 0){{ $task->estimated_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</td>
                            <td>@if ($task->spent_time_days > 0){{ $task->spent_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($task->spent_time_hours > 0){{ $task->spent_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</td>
                            <td align="right">
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}" class="btn see-more"></a>
                                <span class="task-dragndrop" id="ticket-{{ $task->id }}"
                                    data-id="{{ $task->id }}"
                                    data-title="{{ $task->title }}"
                                    data-duration="{{ \Webaccess\ProjectSquareLaravel\Tools\DurationConverter::convertToCalendarDuration($task->estimated_time_days) }}"
                                    data-project="{{ $task->project_id }}"
                                >
                                    <a href="#" class="glyphicon glyphicon-move move-widget" title="Planifier la tâche"></a>
                                </span>
                                <a href="{{ route('tasks_delete', ['id' => $task->id]) }}" class="btn cancel btn-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                {!! $tasks->render() !!}
            </div>
        </div>

        <div class="templates planning-template" style="padding-top: 3rem;">
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
                        <select class="form-control" name="filter_planning_project" id="filter_planning_project">
                            <option value="">{{ trans('projectsquare::tasks.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters_planning['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_planning_user" id="filter_planning_user">
                            <option value="">{{ trans('projectsquare::tasks.filters.by_allocated_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($filters_planning['user'] == $user->id || (!$filters_planning['user'] && $user->id == $currentUserID))selected="selected" @endif>{{ $user->complete_name }}</option>
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
                    <input type="hidden" id="user_id" value="{{ $userID }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/fullcalendar/locale-all.js') }}"></script>
    <script src="{{ asset('js/planning.js') }}"></script>
    <script src="{{ asset('js/tasks.js') }}"></script>
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