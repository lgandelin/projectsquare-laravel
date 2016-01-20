<div class="pull-left">
    <h1 class="logo"><a href="{{ route('dashboard') }}">GATEWAY</a></h1>
</div>

<nav class="pull-right">
    <ul class="notifications">
        <li>
            {{ $user->first_name }} {{ $user->last_name }} <a href="#">Se déconnecter</a>
        </li>
        <li>
            <a href="#">Derniers messages (3)</a>
        </li>

        <li>
            <a href="#">Calendrier (2)</a>
        </li>
    </ul>
</nav>