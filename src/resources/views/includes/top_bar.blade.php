<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}"><img src="{{asset('img/top-bar/logo.png')}}"></a></h1>
    </div>

    <nav class="pull-right">
        <ul class="top-right-menu">
            <li>
                <a href="#" class="notifications-link"> <span class="badge @if (sizeof($notifications) > 0) new-notifications @endif">{{ sizeof($notifications) }}</span></a>

                <div class="notifications" style="display: none;">
                    <span class="glyphicon glyphicon-remove close"></span>
                    <span class="title">
                        @if (sizeof($notifications) > 0)
                            Nouvelles notifications
                        @else
                            Aucune nouvelle notification !
                        @endif
                    </span>
                    @if (sizeof($notifications) > 0)
                        @foreach ($notifications as $notification)
                            <div class="notification" data-id="{{ $notification->id }}">
                                <span class="date">{{ $notification->time }}</span>
                                <span class="badge badge-primary type">
                                    @if ($notification->type == 'MESSAGE_CREATED')
                                        Nouveau message
                                    @elseif ($notification->type == 'EVENT_CREATED')
                                        Nouvel évènement
                                    @elseif ($notification->type == 'TICKET_CREATED')
                                        Nouveau ticket
                                    @elseif ($notification->type == 'FILE_UPLOADED')
                                        Nouveau fichier
                                    @endif
                                </span>
                                <span class="description">
                                    @if ($notification->type == 'MESSAGE_CREATED')
                                        Nouveau message créé par : <strong>{{ $notification->author_name }}</strong>
                                    @elseif ($notification->type == 'EVENT_CREATED')
                                        Nouvel évènement créé : <strong>{{ $notification->event_name }}</strong>
                                    @elseif ($notification->type == 'TICKET_CREATED')
                                        Nouveau ticket créé : <strong>{{ $notification->ticket_title }}</strong>
                                    @elseif ($notification->type == 'FILE_UPLOADED')
                                        Nouveau fichier uploadé : <strong>{{ $notification->file_name }}</strong>
                                    @endif
                                    <br/>
                                    @if (isset($notification->link))
                                        <a class="btn btn-sm button" href="{{ $notification->link }}"><span class="glyphicon glyphicon-eye-open"></span>voir</a>
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