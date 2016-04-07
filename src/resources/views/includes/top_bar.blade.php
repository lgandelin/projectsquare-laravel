<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">projectsquare</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="top-right-menu">
            <li style="position: relative">
                <a href="#" class="notifications-link">{{ trans('projectsquare::notifications.notifications') }} <span class="badge @if($notifications_count > 0) new-notifications @endif">{{ $notifications_count }}</span></a>
                <div class="notifications" style="position: absolute; background: white; border: 2px solid #ccc; padding: 1.5rem; z-index: 999; top: -5rem; left: 2rem">
                    @foreach ($notifications as $notification)
                        <div class="notification">
                            <span class="date">{{ date('d/m/Y H:i', strtotime($notification->date)) }}</span>
                            <span class="badge badge-primary">{{ $notification->type }}</span>
                            <span class="description">Nouvelle notification sur l'entité : {{ $notification->entityID }}</span>
                        </div>
                    @endforeach
                </div>
            </li>

            <li>
                <a href="{{ route('logout') }}">{{ trans('projectsquare::login.logout') }} <span class="glyphicon glyphicon-log-out"></span></a>
            </li>
        </ul>
    </nav>
</div>