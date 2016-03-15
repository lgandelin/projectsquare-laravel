<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">ProjectSquare</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="notifications">
            <li>
                <a href="{{ route('messages_index') }}">Derniers messages @if ($logged_in_user->unread_messages_count > 0)<span class="badge" style="background: #DF5A49;">{{ $logged_in_user->unread_messages_count }}</span>@endif</a>
            </li>

            <li>
                <a href="#">Calendrier <span class="badge" style="background: #DF5A49;">2</span></a>
            </li>

            <li>
                <a href="{{ route('logout') }}">Se déconnecter <span class="glyphicon glyphicon-log-out"></span></a>
            </li>
        </ul>
    </nav>
</div>