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
                    <li><a href="{{ route('tickets') }}">Tickets</a></li>
                    <li><a href="{{ route('messages') }}">Messages</a></li>
                    <li><a href="{{ route('calendar') }}">Calendrier</a></li>
                    <li><a href="{{ route('todo') }}">To do</a></li>
                </ul>
            </li>
            <li>
                <h3><a href="{{ route('agency_index') }}">Agence</a></h3>
                <ul>
                    <li><a href="#">Clients</a></li>
                    <li><a href="{{ route('agency_projects_index') }}">Projets</a></li>
                    <li><a href="#">Utilisateurs</a></li>
                    <li><a href="#">Profils</a></li>
                    <li><a href="#">Réglages</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>