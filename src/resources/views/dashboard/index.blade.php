@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li class="active">{{ trans('projectsquare::dashboard.panel_title') }}</li>
    </ol>
    <div class="dashboard-content">
        <div class="row">
            <div class="col-lg-8 col-md-12">

                <!-- TICKETS -->
                <div class="block">
                    <h3>{{ trans('projectsquare::dashboard.last_tickets') }}</h3>
                    <div class="block-content table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                                <th>{{ trans('projectsquare::tickets.client') }} / {{ trans('projectsquare::tickets.project') }}</th>
                                <th>{{ trans('projectsquare::tickets.type') }}</th>
                                <th>{{ trans('projectsquare::tickets.status') }}</th>
                                <th>{{ trans('projectsquare::tickets.priority') }}</th>
                                <th>{{ trans('projectsquare::generic.action') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td width="40%">{{ $ticket->title }}</td>
                                        <td><a href="{{ route('project_index', ['id' => $ticket->project->id]) }}"><span class="label" style="background: {{ $ticket->project->color }}">{{ $ticket->project->client->name }}</span> {{ $ticket->project->name }}</a></td>
                                        <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                                        <td width="10%">@if (isset($ticket->states[0]))<span class="status status-{{ $ticket->states[0]->status->id }}">{{ $ticket->states[0]->status->name }}</span>@endif</td>
                                        <td>@if (isset($ticket->states[0]))<span class="badge priority-{{ $ticket->states[0]->priority }}">{{ $ticket->states[0]->priority }}</span>@endif</td>
                                        <td>
                                            <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('projectsquare::tickets.see_ticket') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('tickets_add') }}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::tickets.add_ticket') }}</a>
                        <a href="{{ route('tickets_index') }}" class="btn btn-sm btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('projectsquare::tickets.see_tickets') }}</a>
                    </div>
                </div>
                <!-- TICKETS -->

            </div>

            <div class="col-lg-4 col-md-12">

                <!-- MESSAGES -->
                <div class="block last-messages">
                    <h3>{{ trans('projectsquare::dashboard.last_messages') }}</h3>
                    <div class="wrapper">
                        <div class="block-content table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                @foreach ($conversations as $conversation)
                                    <tr class="conversation">
                                        <td>
                                            <span class="badge pull-right count"><span class="number">{{ count($conversation->messages) }}</span> @if (count($conversation->messages) > 1){{ trans('projectsquare::dashboard.messages') }} @else {{ trans('projectsquare::dashboard.message') }} @endif</span>
                                            <a href="{{ route('project_index', ['id' => $conversation->project->id]) }}"><span class="label" style="background: {{ $conversation->project->color }}">{{ $conversation->project->client->name }}</span> {{ $conversation->project->name }}</a> - <strong>{{ $conversation->title }}</strong><br>

                                            <div class="users">
                                                <u>{{ trans('projectsquare::dashboard.conversation_participants') }}</u> :
                                                @foreach ($conversation->users as $i => $user)
                                                    @if ($i > 0),@endif {{ $user->complete_name }}
                                                @endforeach
                                            </div>

                                            @foreach ($conversation->messages as $message)
                                                <div class="message">
                                                    <span class="badge">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span> <span class="glyphicon glyphicon-user"></span> <span class="user_name">{{ $message->user->complete_name }}</span><br/>
                                                    <p class="content">{{ $message->content }}</p>
                                                </div>
                                            @endforeach

                                            <div class="message-inserted"></div>

                                            <div class="message new-message" style="display:none">
                                                <textarea class="form-control" placeholder="{{ trans('projectsquare::dashboard.your_message') }}" rows="4"></textarea>
                                                <button class="btn btn-sm btn-default pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                                                <button class="btn btn-sm btn-success pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('projectsquare::generic.valid') }}</button>
                                            </div>

                                            <div class="submit">
                                                <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-sm btn-primary pull-right"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('projectsquare::messages.see_message') }}</a>
                                                <button class="btn btn-sm btn-success pull-right reply-message" data-id="{{ $message->id }}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span> {{ trans('projectsquare::messages.reply_message') }}</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-success create-conversation"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::messages.add_conversation') }}</button>
                    <a href="{{ route('messages_index') }}" class="btn btn-sm btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('projectsquare::messages.see_messages') }}</a>
                </div>
                <!-- MESSAGES -->

            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">

                <!-- CALENDAR -->
                <div class="block">
                    <h3>{{ trans('projectsquare::calendar.calendar') }} <a style="float:right" href="{{ route('calendar') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('projectsquare::calendar.see_calendar') }}</a></h3>
                    <div class="block-content loading">
                        <div id="calendar"></div>
                    </div>
                </div>
                <!-- CALENDAR -->

            </div>

            <div class="col-lg-6 col-md-12">

                <!-- MONITORING -->
                <div class="block">
                    <h3>{{ trans('projectsquare::dashboard.monitoring_alerts') }}</h3>
                    <div class="block-content table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('projectsquare::dashboard.alert_date') }}</th>
                                <th>{{ trans('projectsquare::dashboard.alert_type') }}</th>
                                <th>{{ trans('projectsquare::dashboard.alert_project') }}</th>
                                <th>{{ trans('projectsquare::dashboard.alert_variables') }}</th>
                                <th>{{ trans('projectsquare::generic.action') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($alerts as $alert)
                                    <tr>
                                        <td>{{ $alert->id }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($alert->created_at)) }}</td>
                                        <td><span class="badge">{{ $alert->type }}</span></td>
                                        <td><a href="{{ route('project_index', ['id' => $alert->project->id]) }}"><span class="label" style="background: {{ $alert->project->color }}">{{ $alert->project->client->name }}</span> {{ $alert->project->name }}</a></td>
                                        <td>
                                            @if ($alert->type == 'WEBSITE_LOADING_TIME')
                                                {{ number_format($alert->variables->loading_time, 2) }}s
                                            @elseif ($alert->type == 'WEBSITE_STATUS_CODE')
                                                {{ $alert->variables->status_code }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('project_monitoring', ['id' => $alert->project->id]) }}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('projectsquare::dashboard.see_project') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- MONITORING -->

                <!-- TASKS -->
                <div class="block">
                    <h3>{{ trans('projectsquare::dashboard.tasks') }}</h3>
                    <div class="block-content">
                        <ul class="tasks">
                            @foreach ($tasks as $task)
                                <li class="task" data-id="{{ $task->id }}" data-status="{{ $task->status }}">
                                    <span class="name @if($task->status == true)task-status-completed @endif">{{ $task->name }}</span>
                                    <input type="hidden" name="id" value="{{ $task->id }}" />
                                    <span class="glyphicon glyphicon-remove btn-delete-task"></span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="form-inline">
                            <div class="form-group">
                                <label for="name">{{ trans('projectsquare::tasks.new-task') }}</label> :
                                <input type="text" class="form-control new-task"  name="name" id="name" required autocomplete="off" />
                                <input type="submit" class="btn btn-success btn-valid-create-task" value="{{ trans('projectsquare::generic.add') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TASKS -->

            </div>
        </div>
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