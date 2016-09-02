@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::planning.planning') }}</li>
    </ol>-->
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

                    <div class="col-md-2">
                        <input class="btn button" type="submit" value="{{ trans('projectsquare::generic.valid') }}" />
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

                    <hr>

                    <div id="my-tasks-list" class="tasks-list" style="display: none; float: left; width: 50%">
                        <h3>{{ trans('projectsquare::planning.allocated_tasks_list') }}</h3>

                        @include('projectsquare::includes.tooltip', [
                         'text' => trans('projectsquare::tooltips.allocated_tasks_list')
                        ])
                        @foreach ($allocated_tasks as $task)
                            <div id="task-{{ $task->id }}"
                                 data-id="{{ $task->id }}"
                                 data-project="@if (isset($task->project)){{ $task->project->id }}@endif"
                                 data-task="{{ $task->id }}"
                                 data-color="@if (isset($task->project)){{ $task->project->color }}@endif"
                                 data-event='{"title":"#{{ $task->id }} - {{ $task->title }}"}'
                                 data-duration="02:00"
                                 class="task fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="@if (isset($task->project))background: {{ $task->project->color }};@endif margin-bottom: 1rem; width: 90%; border: none !important;"
                                    >
                                <div class="fc-content">
                                    <div class="fc-title">
                                        #{{ $task->id }} - {{ $task->title }}
                                        <span class="unallocate-task glyphicon glyphicon-remove pull-right"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="non-allocated-tasks-list" class="tasks-list" style="display: none; float: left; width: 50%">
                        <h3>{{ trans('projectsquare::planning.non_allocated_tasks_list') }}</h3>

                        @include('projectsquare::includes.tooltip', [
                         'text' => trans('projectsquare::tooltips.non_allocated_tasks_list')
                        ])
                        @foreach ($non_allocated_tasks as $task)
                            <div id="task-{{ $task->id }}"
                                 data-id="{{ $task->id }}"
                                 data-project="@if (isset($task->project)){{ $task->project->id }}@endif"
                                 data-task="{{ $task->id }}"
                                 data-color="@if (isset($task->project)){{ $task->project->color }}@endif"
                                 data-event='{"title":"#{{ $task->id }} - {{ $task->title }}"}'
                                 data-duration="02:00"
                                 class="task fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="@if (isset($task->project))background: {{ $task->project->color }};@endif margin-bottom: 1rem; width: 90%; border: none !important;"
                                    >
                                <div class="fc-content"><div class="fc-title">#{{ $task->id }} - {{ $task->title }}</div></div>
                            </div>
                        @endforeach
                    </div>

                    <div id="my-tickets-list" class="tickets-list" style="display: none; clear: both; float: left; width: 50%">
                        <h3>{{ trans('projectsquare::planning.allocated_tickets_list') }}</h3>

                        @include('projectsquare::includes.tooltip', [
                         'text' => trans('projectsquare::tooltips.allocated_tickets_list')
                        ])
                        @foreach ($allocated_tickets as $ticket)
                            <div id="ticket-{{ $ticket->id }}"
                                 data-id="{{ $ticket->id }}"
                                 data-project="@if (isset($ticket->project)){{ $ticket->project->id }}@endif"
                                 data-ticket="{{ $ticket->id }}"
                                 data-color="@if (isset($ticket->project)){{ $ticket->project->color }}@endif"
                                 data-event='{"title":"#{{ $ticket->id }} - {{ $ticket->title }}"}'
                                 data-duration="02:00"
                                 class="ticket fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="@if (isset($ticket->project))background: {{ $ticket->project->color }};@endif margin-bottom: 1rem; width: 90%; border: none !important;"
                            >
                                <div class="fc-content">
                                    <div class="fc-title">
                                        #{{ $ticket->id }} - {{ $ticket->title }}
                                        <span class="unallocate-ticket glyphicon glyphicon-remove pull-right"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="non-allocated-tickets-list" class="tickets-list" style="display: none; float: left; width: 50%">
                        <h3>{{ trans('projectsquare::planning.non_allocated_tickets_list') }}</h3>

                        @include('projectsquare::includes.tooltip', [
                         'text' => trans('projectsquare::tooltips.non_allocated_tickets_list')
                        ])
                        @foreach ($non_allocated_tickets as $ticket)
                            <div id="ticket-{{ $ticket->id }}"
                                 data-id="{{ $ticket->id }}"
                                 data-project="@if (isset($ticket->project)){{ $ticket->project->id }}@endif"
                                 data-ticket="{{ $ticket->id }}"
                                 data-color="@if (isset($ticket->project)){{ $ticket->project->color }}@endif"
                                 data-event='{"title":"#{{ $ticket->id }} - {{ $ticket->title }}"}'
                                 data-duration="02:00"
                                 class="ticket fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="@if (isset($ticket->project))background: {{ $ticket->project->color }};@endif margin-bottom: 1rem; width: 90%; border: none !important;"
                            >
                                <div class="fc-content"><div class="fc-title">#{{ $ticket->id }} - {{ $ticket->title }}</div></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <input type="hidden" class="tickets-current-project" />
                <input type="hidden" class="tickets-current-ticket" />

                <input type="hidden" class="tasks-current-project" />
                <input type="hidden" class="tasks-current-task" />

                <input type="hidden" id="user_id" value="{{ $userID }}" />
            </div>

            <script id="ticket-template" type="text/x-handlebars-template">
                <div id="ticket-@{{id}}"
                     data-id="@{{id}}"
                     data-project="@{{project_id}}"
                     data-ticket="@{{id}}"
                     data-color="@{{color}}"
                     data-event='{"title":"#@{{id}} - @{{title}}"}'
                     data-duration="02:00"
                     class="ticket fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="background: @{{color}}; margin-bottom: 1rem; width: 90%; border: none !important;"
                >
                    <div class="fc-content"><div class="fc-title">
                        #@{{id}} - @{{title}}
                        <span class="unallocate-ticket glyphicon glyphicon-remove pull-right"></span>
                    </div></div>
                </div>
            </script>

            <script id="task-template" type="text/x-handlebars-template">
                <div id="task-@{{id}}"
                     data-id="@{{id}}"
                     data-project="@{{project_id}}"
                     data-task="@{{id}}"
                     data-color="@{{color}}"
                     data-event='{"title":"#@{{id}} - @{{title}}"}'
                     data-duration="02:00"
                     class="task fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="background: @{{color}}; margin-bottom: 1rem; width: 90%; border: none !important;"
                        >
                    <div class="fc-content"><div class="fc-title">
                        #@{{id}} - @{{title}}
                        <span class="unallocate-task glyphicon glyphicon-remove pull-right"></span>
                    </div></div>
                </div>
            </script>
        @endsection

        @section('scripts')
            <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
            <script src="{{ asset('js/vendor/fullcalendar/fullcalendar.min.js') }}"></script>
            <script src="{{ asset('js/vendor/fullcalendar/lang-all.js') }}"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
            <script src="{{ asset('js/planning.js') }}"></script>
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
                        },
                    @endforeach
                ];
            </script>
        </div>
    </div>
@endsection