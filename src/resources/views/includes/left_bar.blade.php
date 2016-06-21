<nav class="col-lg-2 col-md-4 col-sm-4 left-bar">
    <ul>
        <li class="menu">
            <a href="{{ route('dashboard') }}">
                <span class="border img-dashboard"></span>
                <h3 class="title">Tableau de bord</h3>
            </a>
        </li>
        @if (!$is_client)
            <li class="menu menu-2">
                <span class="line projects">
                    <span class="border"></span>
                    <h3 class="title">Projets</h3>
                </span>
                <ul class="sub-menu">
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
            <li class="menu menu-3  @if(in_array($current_route, ['tickets', 'conversations'])) {{ 'encours' }} @endif">
                <span class="line utils">
                    <span class="border"></span>
                    <h3 class="title">Utilitaires</h3>
                </span>
                <ul class="sub-menu">
                    <li class="@if($current_route == 'tickets') {{ 'encours' }} @endif">
                        <a href="{{ route('tickets_index') }}">Tickets</a>
                    </li>
                    <li class="@if($current_route == 'conversations') {{ 'encours' }} @endif">
                        <a href="{{ route('messages_index') }}">Messages</a>
                    </li>
                    <li><a href="{{ route('planning') }}">Planning</a></li>
                    <li><a href="{{ route('tasks_index') }}">{{ trans('projectsquare::tasks.tasks') }}</a></li>
                </ul>
            </li>

            @if ($is_admin)
                <li class="menu">
                    <span class="line agency">
                        <span class="border"></span>
                        <h3 class="title">Agence</h3>
                    </span>
                    <ul class="sub-menu">
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
