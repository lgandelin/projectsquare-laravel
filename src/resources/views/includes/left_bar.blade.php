<div class="col-3">
    <nav>
        <ul>
            <li><h3><a href="{{ route('dashboard') }}">Tableau de bord</a></h3></li>
            <li>
                <h3><a href="{{ route('projects') }}">Projets</a></h3>
                <ul>
                    @foreach ($user->projects as $project)
                    <li>
                        <a href="#">{{ $project->client->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <h3><a href="#">Utilitaires</a></h3>
                <ul>
                    <li><a href="#">Tickets</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="#">Calendrier</a></li>
                    <li><a href="#">To do</a></li>
                </ul>
            </li>
            <li>
                <h3><a href="#">Agence</a></h3>
                <ul>
                    <li><a href="#">Clients</a></li>
                    <li><a href="#">Projets</a></li>
                    <li><a href="#">Utilisateurs</a></li>
                    <li><a href="#">Profils</a></li>
                    <li><a href="#">Réglages</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>