<div class="col-3">
    <nav class="left-bar">
        <ul>
            <li><h3><a href="{{ route('dashboard') }}">Tableau de bord</a></h3></li>
            <li>
                <h3><!--<a href="{{ route('projects') }}">Projets</a>-->Projets</h3>
                <ul>
                    @foreach ($logged_in_user->projects as $project)
                    <li>
                        <span class="label label-primary">{{ $project->client->name }}</span> {{ $project->name }}
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <h3>Utilitaires</h3>
                <ul>
                    <li><a href="{{ route('tickets_index') }}">Tickets</a></li>
                    <li><!--<a href="{{ route('messages') }}">Messages</a>-->Messages</li>
                    <li><!--<a href="{{ route('calendar') }}">Calendrier</a>-->Calendrier</li>
                    <li><!--<a href="{{ route('todo') }}">To do</a>-->To do</li>
                </ul>
            </li>
            <li>
                <h3><!--<a href="{{ route('agency_index') }}">Agence</a>-->Agence</h3>
                <ul>
                    <li><a href="{{ route('clients_index') }}">Clients</a></li>
                    <li><a href="{{ route('projects_index') }}">Projets</a></li>
                    <li><a href="{{ route('users_index') }}">Utilisateurs</a></li>
                    <li><a href="{{ route('roles_index') }}">Profils</a></li>
                    <li><a href="{{ route('ticket_types_index') }}">Types de tickets</a></li>
                    <li><a href="{{ route('ticket_statuses_index') }}">Statuts de tickets</a></li>
                    <li>Réglages</li>
                </ul>
            </li>
        </ul>
    </nav>
</div>