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

                    <div class="notifications" style="display: none;">
                        <ul class="tabs">
                            <li class="current" data-tab="1"><i class="notification-icon"></i></li>
                            <li data-tab="2"><i class="message-icon"></i></li>
                        </ul>

                        <div class="content-tab" data-content="1">
                            @foreach ($notifications as $notification)
                                @if (in_array($notification->type, ['TICKET_CREATED', 'TICKET_UPDATED', 'TASK_CREATED', 'TASK_UPDATED', 'ASSIGNED_TO_PROJECT', 'FILE_UPLOADED']))
                                    <div class="notification" data-id="{{ $notification->id }}">
                                        @if ($notification->type == 'ASSIGNED_TO_PROJECT')
                                            @if ($notification->project)
                                                <span class="project" style="background: {{ $notification->project->color }}">
                                                    {{ $notification->project->name }}
                                                    <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                </span>
                                            @endif

                                            @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                <span class="title">Nouvelle affectation projet</span>
                                                <span class="status">Vous avez été affecté au projet <strong>{{ $notification->project->name}}</strong>.</span>
                                                @if ($notification->relative_date)<span class="relative-date">{{ $notification->relative_date }}</span>@endif
                                            @if (isset($notification->link))</a>@endif
                                        @elseif ($notification->type == 'TICKET_CREATED' || $notification->type == 'TICKET_UPDATED')
                                            @if ($notification->ticket)
                                                @if ($notification->ticket->project)
                                                    <span class="project" style="background: {{ $notification->ticket->project->color }}">
                                                        {{ $notification->ticket->project->name }}
                                                        <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                    </span>
                                                @endif

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    @if ($notification->ticket->author_user)
                                                        @include('projectsquare::includes.avatar', [
                                                            'id' => $notification->ticket->author_user->id,
                                                            'name' => $notification->ticket->author_user->complete_name
                                                        ])
                                                    @endif

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

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    @if ($notification->task->author_user)
                                                        @include('projectsquare::includes.avatar', [
                                                            'id' => $notification->task->author_user->id,
                                                            'name' => $notification->task->author_user->complete_name
                                                        ])
                                                    @endif

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
                                            @if ($notification->file)
                                                @if ($notification->file->project)
                                                    <span class="project" style="background: {{ $notification->file->project->color }}">
                                                        {{ $notification->file->project->name }}
                                                        <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                    </span>
                                                @endif

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    <span class="title">Nouveau fichier uploadé</span>
                                                        <span class="status">{{ $notification->file_name }}</span>
                                                    @if ($notification->relative_date)<span class="relative-date">{{ $notification->relative_date }}</span>@endif
                                                    @if (isset($notification->link))</a>@endif
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>


                        <div class="content-tab" data-content="2" style="display: none">
                            @foreach ($notifications as $notification)
                                @if (in_array($notification->type, ['MESSAGE_CREATED']))
                                    <div class="notification message-notification" data-id="{{ $notification->id }}">
                                        @if ($notification->type == 'MESSAGE_CREATED')
                                            @if ($notification->message)
                                                @if ($notification->message->project)
                                                    <span class="project" style="background: {{ $notification->message->project->color }}">
                                                        {{ $notification->message->project->name }}
                                                        <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
                                                    </span>
                                                @endif

                                                @if (isset($notification->link))<a class="link" href="{{ $notification->link }}">@endif
                                                    @if ($notification->message->user)
                                                        @include('projectsquare::includes.avatar', [
                                                            'id' => $notification->message->user->id,
                                                            'name' => $notification->message->user->firstName . ' ' . $notification->message->user->lastName
                                                        ])
                                                    @endif

                                                    <span class="title">{{ $notification->message->user->firstName . ' ' . $notification->message->user->lastName }}</span>
                                                    <span class="message">{{ $notification->message->content }}</span>
                                                    @if ($notification->relative_date)<span class="relative-date">{{ $notification->relative_date }}</span>@endif
                                                @if (isset($notification->link))</a>@endif

                                                @if ($notification->message->conversation)
                                                    <div class="conversation" id="conversation-{{ $notification->message->conversation->id }}" data-id="{{ $notification->message->conversation->id }}" style="display:none">
                                                        <div class="text-conversation"width="50%">
                                                            {{ str_limit($notification->message->conversation->messages[sizeof($notification->message->conversation->messages) - 1]->content, 100) }}
                                                        </div>

                                                        <div align="center">
                                                            @include('projectsquare::includes.avatar', [
                                                                'id' => $notification->message->conversation->messages[sizeof($notification->message->conversation->messages) - 1]->user->id,
                                                                'name' => $notification->message->conversation->messages[sizeof($notification->message->conversation->messages) - 1]->user->complete_name
                                                            ])
                                                        </div>

                                                        <div>
                                                            {{ date('d/m H:i', strtotime($notification->message->conversation->messages[sizeof($notification->message->conversation->messages) - 1]->created_at)) }}
                                                        </div>

                                                        <div align="center">
                                                            <!--<a href="{{ route('conversations_view', ['id' => $notification->message->conversation->id]) }}" class="btn btn-sm btn-primary see-more" style="margin-right: 1rem"></a>-->
                                                            <button class="button-message pull-right reply-message" data-id="{{ $notification->message->conversation->id }}"><span class="glyphicon-comment"></span></button>
                                                        </div>
                                                    </div>

                                                    <div class="conversation-reply" style="display:none" id="conversation-{{ $notification->message->conversation->id }}-reply" data-id="{{ $notification->message->conversation->id }}">
                                                        <div class="message-inserted"></div>

                                                        <div class="message new-message">
                                                            <textarea class="form-control" placeholder="{{ trans('projectsquare::dashboard.your_message') }}" rows="4"></textarea>
                                                            <button class="btn btn-sm back pull-right cancel-message" data-id="{{ $notification->message->conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                                                            <button class="btn btn-sm valid pull-right valid-message" data-id="{{ $notification->message->conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('projectsquare::generic.valid') }}</button>
                                                        </div>
                                                    </div>
                                                @endif
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
