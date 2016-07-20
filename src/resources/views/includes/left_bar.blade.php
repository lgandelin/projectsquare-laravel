<nav class="left-bar @if ($left_bar == 'closed') left-bar-minified @endif">
    <ul>
        <li class="menu @if($current_route == '/') encours @endif">
            <span class="line img-dashboard">
                <a href="{{ route('dashboard') }}">
                    <span class="border"></span>
                    <h3 class="title">Tableau de bord</h3>
                </a>
            </span>
        </li>
        @if (!$is_client)
            <li class="menu @if(preg_match('/project_/', Route::current()->getName())) {{ 'encours' }} @endif">
                <span class="line projects">
                    <span class="border"></span>
                    <h3 class="title">Projets</h3>
                </span>
                <ul class="sub-menu">
                    @foreach ($logged_in_user->projects as $project)
                    <li class="@if (isset($current_project_id) && $current_project_id == $project->id) encours @endif" style="border-left: 3px solid {{ $project->color }}">
                        <?php $route = preg_match('/project_/', Route::current()->getName()) ? Route::current()->getName() : 'project_index'; ?>
                        <a href="{{ route($route, ['id' => $project->id]) }}" @if (preg_match('/project_/', Route::current()->getName()) && isset($current_project_id) && $current_project_id == $project->id) style="color: #c8dc1e" @endif">
                            <!--{{ $project->name }}-->
                            <span title="{{ $project->name }}">{{ $project->client->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="menu @if(in_array($current_route, ['tasks', 'tickets', 'conversations', 'planning', 'monitoring'])) {{ 'encours' }} @endif">
                <span class="line utils">
                    <span class="border"></span>
                    <h3 class="title">Utilitaires</h3>
                </span>
                <ul class="sub-menu">
                    <li class="@if($current_route == 'tasks') {{ 'encours' }} @endif">
                        <a href="{{ route('tasks_index') }}">Tâches</a>
                    </li>

                    <li class="@if($current_route == 'tickets') {{ 'encours' }} @endif">
                        <a href="{{ route('tickets_index') }}">Tickets</a>
                    </li>

                    <li class="@if($current_route == 'conversations') {{ 'encours' }} @endif">
                        <a href="{{ route('messages_index') }}">Messages</a>
                    </li>

                    <li class="@if($current_route == 'planning') {{ 'encours' }} @endif"><a href="{{ route('planning') }}">Planning</a></li>

                    <li class="@if($current_route == 'monitoring') {{ 'encours' }} @endif">
                        <a href="{{ route('monitoring_index') }}">Alertes monitoring</a>
                    </li>
                </ul>
            </li>

            @if ($is_admin)
                <li class="menu @if(in_array($current_route, ['agency/clients', 'agency/projects', 'agency/users', 'agency/roles', 'ticket_types', 'ticket_statuses'])) {{ 'encours' }} @endif">
                    <span class="line agency">
                        <span class="border"></span>
                        <h3 class="title">Agence</h3>
                    </span>
                    <ul class="sub-menu">
                        <li class="@if($current_route == 'agency/clients') {{ 'encours' }} @endif"><a href="{{ route('clients_index') }}">Clients</a></li>
                        <li class="@if($current_route == 'agency/projects') {{ 'encours' }} @endif"><a href="{{ route('projects_index') }}">Projets</a></li>
                        <li class="@if($current_route == 'agency/users') {{ 'encours' }} @endif"><a href="{{ route('users_index') }}">Collaborateurs</a></li>
                        <li class="@if($current_route == 'agency/roles') {{ 'encours' }} @endif"><a href="{{ route('roles_index') }}">Profils</a></li>
                        <li class="@if($current_route == 'ticket_types') {{ 'encours' }} @endif"><a href="{{ route('ticket_types_index') }}">Types de tickets</a></li>
                        <li class="@if($current_route == 'ticket_statuses') {{ 'encours' }} @endif"><a href="{{ route('ticket_statuses_index') }}">Statuts de tickets</a></li>
                    </ul>
                </li>
            @endif

            <li class="menu">
                <span class="line toggle-left-bar glyphicon glyphicon-triangle-@if ($left_bar == 'closed'){{'right'}}@else{{'left'}}@endif">
                    <h3 class="title"></h3>
                </span>
            </li>
        @endif
    </ul>
</nav>
