<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}"><img src="{{asset('img/top-bar/logo.png')}}"></a></h1>
    </div>

    @if (isset($is_client) && $is_client && sizeof($in_progress_projects) > 1)
        <form action="{{ route('projects_client_switch') }}" method="post" id="project-switcher-form" class="project-switcher">
            <label for="project_id">{{ trans('projectsquare::dashboard.project') }} :</label>
            <select class="form-control" name="project_id" onchange="$('#project-switcher-form').submit()">
                @foreach ($in_progress_projects as $project)
                    <option value="{{ $project->id }}" @if ($current_project && $current_project->id == $project->id)selected="selected"@endif>{{ $project->name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="route" value="{{ $current_route }}" />
            {{ csrf_field() }}
        </form>
    @endif

    <nav class="pull-right">
        <ul class="top-right-menu">
            @if (isset($todos))
                <li class="todo">
                     <a href="#" class="todos-link">
                        <span class="badge todos-number">@if(isset($todos_count)){{ $todos_count }}@else 0 @endif</span></a>
                     </a>

                    <div class="todos" style="display: none;">

                        <span class="title">
                            {{ trans('projectsquare::top_bar.lists_of_tasks') }}
                        </span>

                        <span class="no-todos" @if (sizeof($todos) == 0)style="display: block"@else style="display:none"@endif>{{ trans('projectsquare::top_bar.no_current_task') }}</span>

                        <ul>
                            @foreach ($todos as $todo)
                                <li class="todo @if($todo->status == true)todo-status-completed @endif" data-id="{{ $todo->id }}" data-status="{{ $todo->status }}"><span class="name">{{ $todo->name }}</span><input type="hidden" name="id" value="{{ $todo->id }}" /><span class="glyphicon glyphicon-remove btn-delete-todo"></span></li>
                            @endforeach
                        </ul>

                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control new-todo" placeholder="{{ trans('projectsquare::top_bar.new_todo') }}" name="name" id="name" required autocomplete="off" />
                                <button type="submit" class="btn add btn-valid-create-todo"></button>
                            </div>
                        </div>
                    </div>
                </li>
            @endif

            @if (isset($notifications))
                <li>
                    <a href="#" class="notifications-link"> <span class="badge @if (sizeof($notifications) > 0) new-notifications @endif">{{ sizeof($notifications) }}</span></a>

                    <div class="notifications" {{--style="display: none;"--}}>
                        <ul class="tabs">
                            <li class="current" data-tab="1"><i class="notification-icon"></i></li>
                            <li data-tab="2"><i class="message-icon"></i></li>
                        </ul>

                        <div class="content-tab" data-content="1">
                            @foreach ($notifications as $notification)
                                @if (in_array($notification->type, ['TICKET_CREATED', 'TICKET_UPDATED', 'TASK_CREATED', 'TASK_UPDATED']))
                                    <div class="notification" data-id="{{ $notification->id }}">
                                        @if ($notification->type == 'EVENT_CREATED')
                                        @elseif ($notification->type == 'TICKET_CREATED' || $notification->type == 'TICKET_UPDATED')
                                            @if ($notification->ticket)
                                                @if ($notification->ticket->author_user)
                                                    @include('projectsquare::includes.avatar', [
                                                        'id' => $notification->ticket->author_user->id,
                                                        'name' => $notification->ticket->author_user->complete_name
                                                    ])
                                                @endif

                                                @if ($notification->ticket->project)
                                                    <span class="project" style="background: {{ $notification->ticket->project->color }}">
                                                        {{ $notification->ticket->project->name }}
                                                        <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                    </span>
                                                @endif

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    <span class="title">{{ $notification->ticket->title }}</span>
                                                    <span class="status">Statut : {{ $notification->ticket->lastState->status->name }}</span>
                                                    @if ($notification->relative_date)<span class="relative-date">{{ $notification->relative_date }}</span>@endif
                                                @if (isset($notification->link))</a>@endif
                                            @endif
                                        @elseif ($notification->type == 'TASK_CREATED' || $notification->type == 'TASK_UPDATED')
                                            @if ($notification->task)
                                                @if ($notification->task->project)
                                                    <span class="project" style="background: {{ $notification->task->project->color }}">
                                                        {{ $notification->task->project->name }}
                                                        <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                    </span>
                                                @endif

                                                @if ($notification->task->author_user)
                                                    @include('projectsquare::includes.avatar', [
                                                        'id' => $notification->task->author_user->id,
                                                        'name' => $notification->task->author_user->complete_name
                                                    ])
                                                @endif

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    <span class="title">{{ $notification->task->title }}</span>
                                                    <span class="status">Statut :
                                                        @if ($notification->task->statusID == 1)A faire
                                                        @elseif ($notification->task->statusID == 2)En cours
                                                        @elseif ($notification->task->statusID == 3)Terminé
                                                        @endif
                                                    </span>
                                                    @if ($notification->relative_date)<span class="relative-date">{{ $notification->relative_date }}</span>@endif
                                                    @if (isset($notification->link))</a>@endif
                                            @endif
                                        @elseif ($notification->type == 'FILE_UPLOADED')
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>


                        <div class="content-tab" data-content="2" style="display: none">
                            @foreach ($notifications as $notification)
                                @if (in_array($notification->type, ['MESSAGE_CREATED']))
                                    <div class="notification" data-id="{{ $notification->id }}">
                                        @if ($notification->type == 'MESSAGE_CREATED')
                                            @if ($notification->message)
                                                @if ($notification->message->project)
                                                    <span class="project" style="background: {{ $notification->message->project->color }}">
                                                                {{ $notification->message->project->name }}
                                                        <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                            </span>
                                                @endif

                                                @if ($notification->message->user)
                                                    @include('projectsquare::includes.avatar', [
                                                        'id' => $notification->message->user->id,
                                                        'name' => $notification->message->user->firstName . ' ' . $notification->message->user->lastName
                                                    ])
                                                @endif

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    <span class="title">{{ $notification->message->user->firstName . ' ' . $notification->message->user->lastName }}</span>
                                                    <span class="message">{{ $notification->message->content }}</span>
                                                    @if ($notification->relative_date)<span class="relative-date">{{ $notification->relative_date }}</span>@endif
                                                    @if (isset($notification->link))</a>@endif
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </li>
            @endif

            @if (isset($logged_in_user))
                <li>
                    <a href="{{ route('my') }}" title="{{ trans('projectsquare::my.panel_title') }}">
                        @include('projectsquare::includes.avatar', [
                            'id' => $logged_in_user->id,
                            'name' => $logged_in_user->complete_name
                        ])
                    </a>
                </li>

                <li class="out">
                    <a href="{{ route('logout') }}" class="log-out" ></a>
                </li>
            @endif
        </ul>
    </nav>
</div>

@include ('projectsquare::templates.new-todo')
