<div class="col-3">
    <nav class="left-bar">
        <ul>
            <li><h3><a href="{{ route('dashboard') }}">Tableau de bord</a></h3></li>
            <li>
                <h3><a href="{{ route('projects') }}">Projets</a></h3>
                <ul>
                    @foreach ($user->projects as $project)
                    <li>
                        {{ $project->client->name }}
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <h3>Utilitaires</h3>
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
                    <li>Clients</li>
                    <li><a href="{{ route('projects_index') }}">Projets</a></li>
                    <li><a href="{{ route('users_index') }}">Utilisateurs</a></li>
                    <li><a href="{{ route('roles_index') }}">Profils</a></li>
                    <li>Réglages</li>
                </ul>
            </li>
        </ul>
    </nav>
</div>