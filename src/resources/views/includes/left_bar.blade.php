<nav class="left-bar">
    <ul>
        <li><h3><a href="{{ route('dashboard') }}">Tableau de bord</a></h3></li>
        <li>
            <h3>Projets</h3>
            <ul class="projects-list">
                @foreach ($logged_in_user->projects as $project)
                <li class="@if (isset($current_project_id) && $current_project_id == $project->id) current @endif">
                    <a href="{{ route('project_index', ['id' => $project->id]) }}">
                        <span class="client">
                            <span class="label project-status-{{ $project->status }}">{{ $project->client->name }}</span>
                        </span>
                        {{ $project->name }}
                        @if (isset($current_project_id) && $current_project_id == $project->id)
                            <i class="pull-right glyphicon glyphicon-ok"></i>
                        @endif
                    </a>
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
                <li><a href="{{ route('to_do_index') }}">To do</a></li>
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
