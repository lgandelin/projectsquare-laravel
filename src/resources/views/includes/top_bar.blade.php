<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">projectsquare</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="top-right-menu">
            <li>
                <a href="#" class="notifications-link">{{ trans('projectsquare::notifications.notifications') }} <span class="badge @if (sizeof($notifications) > 0) new-notifications @endif">{{ sizeof($notifications) }}</span></a>

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
                                <span class="badge badge-primary type">{{ $notification->type }}</span>
                                <span class="description">
                                    @if ($notification->type == 'MESSAGE_CREATED')
                                        Nouveau message créé par : <strong>{{ $notification->author_name }}</strong>
                                    @elseif ($notification->type == 'EVENT_CREATED')
                                        Nouvel évènement créé : <strong>{{ $notification->event_name }}</strong>
                                    @endif
                                    <br/>
                                    <a class="btn btn-sm btn-primary" href="{{ $notification->link }}"><span class="glyphicon glyphicon-share-alt"></span>voir</a>
                                    <span class="glyphicon glyphicon-eye-open pull-right status not-read"></span>
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </li>

            <li>
                <a href="{{ route('logout') }}">{{ trans('projectsquare::login.logout') }} <span class="glyphicon glyphicon-log-out"></span></a>
            </li>
        </ul>
    </nav>
</div>

@include ('projectsquare::templates.new-notification')