<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">GATEWAY</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="notifications">
            <li>
                {{ $logged_in_user->first_name }} {{ $logged_in_user->last_name }} <a href="{{ route('logout') }}">Se déconnecter</a>
            </li>
            <li>
                <a href="#">Derniers messages (3)</a>
            </li>

            <li>
                <a href="#">Calendrier (2)</a>
            </li>
        </ul>
    </nav>
</div>