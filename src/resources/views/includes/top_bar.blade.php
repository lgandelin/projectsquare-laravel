<div class="top-bar">
    <div class="pull-left">
        <h1 class="logo"><a href="{{ route('dashboard') }}">GATEWAY</a></h1>
    </div>

    <nav class="pull-right">
        <ul class="notifications">
            <li>
                <a href="#">Derniers messages <span class="badge" style="background: #DF5A49;">3</span></a>
            </li>

            <li>
                <a href="#">Calendrier <span class="badge" style="background: #DF5A49;">2</span></a>
            </li>

            <li>
                <a href="{{ route('logout') }}">Se déconnecter <span class="glyphicon glyphicon-log-out"</a>
            </li>
        </ul>
    </nav>
</div>