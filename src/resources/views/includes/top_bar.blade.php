<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">projectsquare</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="notifications">
            <li>
                <a href="{{ route('notifications') }}">{{ trans('projectsquare::notifications.notifications') }} <span class="badge @if($notifications_count > 0) new-notifications @endif">{{ $notifications_count }}</span></a>
            </li>

            <li>
                <a href="{{ route('logout') }}">{{ trans('projectsquare::login.logout') }} <span class="glyphicon glyphicon-log-out"></span></a>
            </li>
        </ul>
    </nav>
</div>