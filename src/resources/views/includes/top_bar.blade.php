<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}"><img src="{{asset('img/top-bar/logo.png')}}"></a></h1>
    </div>

    <nav class="pull-right">
        <ul class="top-right-menu">

            <li class="todo">
                 <a href="#" class="todos-link">
                    <span class="badge todos-number">{{ $todos_count }}</span></a>
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
                            <input type="text" class="form-control new-todo" placeholder="nouvelle tâche" name="name" id="name" required autocomplete="off" />
                            <button type="submit" class="btn add btn-valid-create-todo" />
                        </div>
                    </div>
                </div>
               
            </li>
            <li>
                <a href="#" class="notifications-link"> <span class="badge @if (sizeof($notifications) > 0) new-notifications @endif">{{ sizeof($notifications) }}</span></a>

                <div class="notifications" style="display: none;">
                    <span class="title">
                        @if (sizeof($notifications) > 0)
                           {{ trans('projectsquare::top_bar.news_notifications') }}
                        @else
                            {{ trans('projectsquare::top_bar.no_new_notification') }}
                        @endif
                    </span>
                    @if (sizeof($notifications) > 0)
                        @foreach ($notifications as $notification)
                            <div class="notification" data-id="{{ $notification->id }}">
                                <span class="date">{{ $notification->time }}</span>
                                <span class="badge badge-primary type">
                                    @if ($notification->type == 'MESSAGE_CREATED')
                                        {{ trans('projectsquare::top_bar.new_message') }}
                                    @elseif ($notification->type == 'EVENT_CREATED')
                                        {{ trans('projectsquare::top_bar.new_event') }}
                                    @elseif ($notification->type == 'TICKET_CREATED')
                                        {{ trans('projectsquare::top_bar.new_ticket') }}
                                    @elseif ($notification->type == 'TASK_CREATED')
                                        {{ trans('projectsquare::top_bar.new_task') }}
                                    @elseif ($notification->type == 'FILE_UPLOADED')
                                        {{ trans('projectsquare::top_bar.new_file') }}
                                    @endif
                                </span>
                                <span class="description">
                                    @if ($notification->type == 'MESSAGE_CREATED')
                                        {{ trans('projectsquare::top_bar.new_message_created') }} <strong>{{ $notification->author_name }}</strong>
                                    @elseif ($notification->type == 'EVENT_CREATED')
                                        {{ trans('projectsquare::top_bar.new_event_created') }} <strong>{{ $notification->event_name }}</strong>
                                    @elseif ($notification->type == 'TICKET_CREATED')
                                        {{ trans('projectsquare::top_bar.new_ticket_created') }} <strong>{{ $notification->ticket_title }}</strong>
                                    @elseif ($notification->type == 'TASK_CREATED')
                                        {{ trans('projectsquare::top_bar.new_task_created') }} <strong>{{ $notification->task_title }}</strong>
                                    @elseif ($notification->type == 'FILE_UPLOADED')
                                        {{ trans('projectsquare::top_bar.new_file_created') }} <strong>{{ $notification->file_name }}</strong>
                                    @endif
                                    <br/>
                                    @if (isset($notification->link))
                                        <a class="btn btn-sm button" href="{{ $notification->link }}"><span class="glyphicon glyphicon-eye-open"></span>{{ trans('projectsquare::top_bar.see') }}</a>
                                    @endif
                                    <span class="glyphicon glyphicon-remove pull-right status not-read"></span>
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </li>

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
        </ul>
    </nav>
</div>

@include ('projectsquare::templates.new-notification')
@include ('projectsquare::templates.new-todo')
