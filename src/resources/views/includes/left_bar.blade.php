<nav class="left-bar @if ($left_bar == 'closed') left-bar-minified @endif">
    <ul>
        <li class="menu @if($current_route == '/') encours @endif">
            <span class="line img-dashboard">
                <a href="{{ route('dashboard') }}">
                    <span class="border"></span>
                    <h3 class="title">{{ trans('projectsquare::left_bar.dashboard') }}</h3>
                </a>
            </span>
        </li>
        @if (!$is_client)
            <li class="menu @if(preg_match('/project_/', Route::current()->getName())) {{ 'encours' }} @endif">
                <span class="line projects">
                    <span class="border"></span>
                    <h3 class="title">{{ trans('projectsquare::left_bar.projects') }}</h3>
                </span>
                <ul class="sub-menu">
                    @foreach ($logged_in_user->projects as $project)
                    <li class="@if (isset($current_project_id) && $current_project_id == $project->id) encours @endif" style="border-left: 3px solid {{ $project->color }}">
                        <?php $route = preg_match('/project_/', Route::current()->getName()) ? Route::current()->getName() : 'project_index'; ?>
                        <a href="{{ route($route, ['id' => $project->id]) }}" @if (preg_match('/project_/', Route::current()->getName()) && isset($current_project_id) && $current_project_id == $project->id) style="color: #c8dc1e" @endif">
                            <!--{{ $project->name }}-->
                            <span title="{{ $project->name }}">{{$project->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="menu @if(in_array($current_route, ['tasks', 'tickets', 'conversations', 'planning', 'monitoring']) ||
            preg_match('/tasks/', $current_route) || preg_match('/tickets/', $current_route) ||
            preg_match('/conversations/', $current_route)) {{ 'encours' }} @endif">
                <span class="line utils">
                    <span class="border"></span>
                    <h3 class="title">{{ trans('projectsquare::left_bar.utilities') }}</h3>
                </span>
                <ul class="sub-menu">
                    <li class="@if(preg_match('/tasks/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('tasks_index') }}">{{ trans('projectsquare::left_bar.tasks') }}</a>
                    </li>

                    <li class="@if(preg_match('/tickets/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('tickets_index') }}">{{ trans('projectsquare::left_bar.tickets') }}</a>
                    </li>

                    <li class="@if(preg_match('/conversation/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('messages_index') }}">{{ trans('projectsquare::left_bar.messages') }}</a>
                    </li>

                    <li class="@if($current_route == 'planning') {{ 'encours' }} @endif"><a href="{{ route('planning') }}">Planning</a></li>

                    <li class="@if($current_route == 'monitoring') {{ 'encours' }} @endif">
                        <a href="{{ route('monitoring_index') }}">{{ trans('projectsquare::left_bar.monitoring_alerts') }}</a>
                    </li>
                </ul>
            </li>

            @if ($is_admin)
                <li class="menu @if(in_array($current_route, ['agency/clients', 'agency/projects', 'agency/users', 'agency/roles', 'ticket_types', 'ticket_statuses']) ||
                preg_match('/agency\/clients/', $current_route) || preg_match('/agency\/projects/', $current_route) ||
                preg_match('/agency\/users/', $current_route) || preg_match('/agency\/roles/', $current_route) ||
                preg_match('/ticket_types/', $current_route) || preg_match('/ticket_statuses/', $current_route) ||
                preg_match('/settings/', $current_route)) {{ 'encours' }} @endif">
                    <span class="line agency">
                        <span class="border"></span>
                        <h3 class="title">{{ trans('projectsquare::left_bar.agency') }}</h3>
                    </span>
                    <ul class="sub-menu">
                        <li class="@if(preg_match('/agency\/clients/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('clients_index') }}">{{ trans('projectsquare::left_bar.clients') }}</a></li>
                        <li class="@if(preg_match('/agency\/projects/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('projects_index') }}">{{ trans('projectsquare::left_bar.projects') }}</a></li>
                        <li class="@if(preg_match('/agency\/users/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('users_index') }}">{{ trans('projectsquare::left_bar.users') }}</a></li>
                        <li class="@if(preg_match('/agency\/roles/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('roles_index') }}">{{ trans('projectsquare::left_bar.profils') }}</a></li>
                        <li class="@if(preg_match('/ticket_types/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('ticket_types_index') }}">{{ trans('projectsquare::left_bar.tickets_types') }}</a></li>
                        <li class="@if(preg_match('/ticket_statuses/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('ticket_statuses_index') }}">{{ trans('projectsquare::left_bar.tickets_statuses') }}</a></li>
                        <li class="@if(preg_match('/settings/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('settings_index') }}">{{ trans('projectsquare::left_bar.settings') }}</a></li>
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
