<nav class="col-lg-2 col-md-4 col-sm-4 left-bar">
    <ul>
        <li>
        <a href="{{ route('dashboard') }}" class="dashboard">
            <span class="img"></span>
            <h3>Tableau de bord</h3>
        </a>
        </li>
        @if (!$is_client)
            <li>
                <span class="title projects">
                    <span class="img"></span>
                    <h3>Projets</h3>
                </span>
                <ul class="projects-list">
                    @foreach ($logged_in_user->projects as $project)
                    <li class="@if (isset($current_project_id) && $current_project_id == $project->id) current @endif">
                        <?php $route = preg_match('/project_/', Route::current()->getName()) ? Route::current()->getName() : 'project_index'; ?>
                        <a href="{{ route($route, ['id' => $project->id]) }}">
                            {{ $project->name }}
                            @if (isset($current_project_id) && $current_project_id == $project->id)
                                <i class="glyphicon glyphicon-ok"></i>
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <span class="title utils">
                    <span class="img"></span>
                    <h3>Utilitaires</h3>
                </span>
                <ul>
                    <li><a href="{{ route('tickets_index') }}">Tickets</a></li>
                    <li><a href="{{ route('messages_index') }}">Messages</a></li>
                    <li><a href="{{ route('planning') }}">Planning</a></li>
                    <li><a href="{{ route('tasks_index') }}">{{ trans('projectsquare::tasks.tasks') }}</a></li>
                </ul>
            </li>

            @if ($is_admin)
                <li>
                    <span class="title agency">
                        <span class="img"></span>
                        <h3>Agence</h3>
                    </span>
                    <ul>
                        <li><a href="{{ route('clients_index') }}">Clients</a></li>
                        <li><a href="{{ route('projects_index') }}">Projets</a></li>
                        <li><a href="{{ route('users_index') }}">Utilisateurs</a></li>
                        <li><a href="{{ route('roles_index') }}">Profils</a></li>
                        <li><a href="{{ route('ticket_types_index') }}">Types de tickets</a></li>
                        <li><a href="{{ route('ticket_statuses_index') }}">Statuts de tickets</a></li>
                    </ul>
                </li>
            @endif
        @endif
    </ul>
</nav>
