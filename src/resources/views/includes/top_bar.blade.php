<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">projectsquare</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="notifications">
            <li>
                <a href="{{ route('messages_index') }}">{{ trans('projectsquare::messages.last_messages') }} @if ($unread_messages_count > 0)<span class="badge" style="background: #DF5A49;">{{ $unread_messages_count }}</span>@endif</a>
            </li>

            <li>
                <a href="{{ route('calendar') }}">{{ trans('projectsquare::calendar.calendar') }}</a>
            </li>

            <li>
                <a href="{{ route('logout') }}">{{ trans('projectsquare::login.logout') }} <span class="glyphicon glyphicon-log-out"></span></a>
            </li>
        </ul>
    </nav>
</div>