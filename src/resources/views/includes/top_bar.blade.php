<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">projectsquare</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="notifications">
            <li>
                <a href="{{ route('notifications') }}">{{ trans('projectsquare::notifications.notifications') }} @if ($notifications_count > 0)<span class="badge" style="background: #DF5A49;">{{ $notifications_count }}</span>@endif</a>
            </li>

            <li>
                <a href="{{ route('logout') }}">{{ trans('projectsquare::login.logout') }} <span class="glyphicon glyphicon-log-out"></span></a>
            </li>
        </ul>
    </nav>
</div>