<nav class="left-bar @if ($left_bar == 'closed') left-bar-minified @endif">
    <ul>
        <li class="menu @if ($current_route == 'dashboard') encours @endif">
            <span class="line dashboard" title="{{ trans('projectsquare::left_bar.dashboard') }}">
                <a href="{{ route('dashboard') }}">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">{{ trans('projectsquare::left_bar.dashboard') }}</h3>
                </a>
            </span>
        </li>
        @if (!$is_client)
            <li class="menu @if (preg_match('/project_/', $current_route)) {{ 'encours' }} @endif">
                <span class="line projects" title="{{ trans('projectsquare::left_bar.projects') }}">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">{{ trans('projectsquare::left_bar.projects') }}</h3>
                </span>
                <ul class="sub-menu">
                    @foreach ($logged_in_user->projects as $project)
                    <li class="@if (isset($current_project_id) && $current_project_id == $project->id) encours @endif" style="border-left: 3px solid {{ $project->color }}">
                        <?php $route = preg_match('/project_/', $current_route) ? $current_route : 'project_index'; ?>
                        <a href="{{ route($route, ['id' => $project->id]) }}" @if (preg_match('/project_/', $current_route) && isset($current_project_id) && $current_project_id == $project->id) style="color: #c8dc1e" @endif">
                            <!--{{ $project->name }}-->
                            <span title="{{ $project->name }}">{{$project->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>

            <li class="menu @if (preg_match('/tasks_/', $current_route) ||
            preg_match('/tickets_/', $current_route) ||
            preg_match('/conversations_/', $current_route) ||
            preg_match('/planning/', $current_route) ||
            preg_match('/occupation/', $current_route) ||
            preg_match('/progress/', $current_route) ||
            preg_match('/monitoring_/', $current_route)) {{ 'encours' }} @endif">
                <span class="line tools" title="{{ trans('projectsquare::left_bar.tools') }}">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">{{ trans('projectsquare::left_bar.tools') }}</h3>
                </span>
                <ul class="sub-menu">
                    <li class="@if (preg_match('/tasks_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('tasks_index') }}">{{ trans('projectsquare::left_bar.tasks') }}</a>
                    </li>

                    <li class="@if (preg_match('/tickets_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('tickets_index') }}">{{ trans('projectsquare::left_bar.tickets') }}</a>
                    </li>

                    <li class="@if (preg_match('/conversations_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('conversations_index') }}">{{ trans('projectsquare::left_bar.messages') }}</a>
                    </li>

                    <li class="@if (preg_match('/planning/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('planning') }}">Planning</a></li>

                    <li class="@if (preg_match('/monitoring_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('monitoring_index') }}">{{ trans('projectsquare::left_bar.monitoring_alerts') }}</a>
                    </li>
                </ul>
            </li>

            @if ($is_admin)
                <li class="menu @if (preg_match('/clients_/', $current_route) ||
                 preg_match('/projects_/', $current_route) ||
                 preg_match('/occupation/', $current_route) ||
                 preg_match('/progress/', $current_route)) {{ 'encours' }} @endif">
                    <span class="line management" title="{{ trans('projectsquare::left_bar.management') }}">
                        <span class="border"><span class="icon"></span></span>
                        <h3 class="title">{{ trans('projectsquare::left_bar.management') }}</h3>
                    </span>
                    <ul class="sub-menu">
                        <li class="@if (preg_match('/progress/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('progress') }}">Avancement</a></li>
                        <li class="@if (preg_match('/occupation/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('occupation') }}">Occupation</a></li>
                        <li class="@if (preg_match('/clients_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('clients_index') }}">{{ trans('projectsquare::left_bar.clients') }}</a></li>
                        <li class="@if (preg_match('/projects_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('projects_index') }}">{{ trans('projectsquare::left_bar.projects') }}</a></li>
                    </ul>
                </li>
            @endif

            @if ($is_admin)
                <li class="menu @if (preg_match('/roles_/', $current_route) ||
                 preg_match('/ticket_types_/', $current_route) ||
                 preg_match('/ticket_statuses_/', $current_route) ||
                 preg_match('/users_/', $current_route) ||
                 preg_match('/settings/', $current_route)) {{ 'encours' }} @endif">
                    <span class="line administration" title="{{ trans('projectsquare::left_bar.administration') }}">
                        <span class="border"><span class="icon"></span></span>
                        <h3 class="title">{{ trans('projectsquare::left_bar.administration') }}</h3>
                    </span>
                    <ul class="sub-menu">
                        <li class="@if (preg_match('/users_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('users_index') }}">{{ trans('projectsquare::left_bar.users') }}</a></li>
                        <li class="@if (preg_match('/roles_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('roles_index') }}">{{ trans('projectsquare::left_bar.profils') }}</a></li>
                        <li class="@if (preg_match('/ticket_types_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('ticket_types_index') }}">{{ trans('projectsquare::left_bar.tickets_types') }}</a></li>
                        <li class="@if (preg_match('/ticket_statuses_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('ticket_statuses_index') }}">{{ trans('projectsquare::left_bar.tickets_statuses') }}</a></li>
                        <li class="@if (preg_match('/settings/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('settings_index') }}">{{ trans('projectsquare::left_bar.settings') }}</a></li>
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
