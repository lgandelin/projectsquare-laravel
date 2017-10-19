<nav class="left-bar @if ($left_bar == 'closed') left-bar-minified @endif" id="left-bar">
    <ul>
        <li class="menu @if ($current_route == 'dashboard') encours @endif">
            <span class="line dashboard" title="{{ __('projectsquare::left_bar.dashboard') }}">
                <a href="{{ route('dashboard') }}">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">{{ __('projectsquare::left_bar.dashboard') }}</h3>
                </a>
            </span>
        </li>

        @if (!$is_client)
            <li class="menu @if (preg_match('/project_/', $current_route)) {{ 'encours' }} @endif @if ($left_bar_projects == 'closed') {{ 'submenu-closed' }} @endif">
                <span class="line projects" title="{{ __('projectsquare::left_bar.my_projects') }}" data-id="projects">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">{{ __('projectsquare::left_bar.my_projects') }}</h3>
                    <span class="toggle-childs"></span>
                </span>
                <ul class="sub-menu sub-menu-projects">
                    <li class="filter-project filter-disabled">
                        <input type="text" class="form-control" placeholder="Filtrer..." />
                    </li>

                    @if ($is_admin)
                        <li class="add-project">
                            <a href="{{ route('projects_add') }}">{{ __('projectsquare::left_bar.add_project') }} <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </li>
                    @endif

                    @foreach ($in_progress_projects as $project)
                        <li class="@if (isset($current_project_id) && $current_project_id == $project->id) encours @endif" style="border-left: 3px solid {{ $project->color }}">
                            <?php $route = preg_match('/project_/', $current_route) ? $current_route : 'project_tasks'; ?>
                            <a href="{{ route($route, ['id' => $project->id]) }}" @if (preg_match('/project_/', $current_route) && isset($current_project_id) && $current_project_id == $project->id) style="color: #c8dc1e" @endif">
                                <span title="@if (isset($project->client))[{{ $project->client->name }}] @endif{{ $project->name }}">{{$project->name}}</span>
                            </a>
                            @if (isset($project->client))<span style="display:none">{{ $project->client->name }}</span>@endif
                        </li>
                    @endforeach

                    @if (sizeof($archived_projects) > 0)
                        <li class="archived-project archived-project-title filter-disabled"><i class="glyphicon glyphicon-folder-open"></i> Archives</li>
                    @endif

                    @foreach ($archived_projects as $project)
                        <li class="archived-project @if (isset($current_project_id) && $current_project_id == $project->id) encours @endif">
                            <?php $route = preg_match('/project_/', $current_route) ? $current_route : 'project_tasks'; ?>
                            <a href="{{ route($route, ['id' => $project->id]) }}" @if (preg_match('/project_/', $current_route) && isset($current_project_id) && $current_project_id == $project->id) style="color: #c8dc1e" @endif">
                                <span title="@if (isset($project->client))[{{ $project->client->name }}] @endif{{ $project->name }}">{{$project->name}}</span>
                            </a>
                            @if (isset($project->client))<span style="display:none">{{ $project->client->name }}</span>@endif
                        </li>
                    @endforeach
                </ul>
            </li>

            <li class="menu @if (preg_match('/^tasks_/', $current_route) ||
            preg_match('/^tickets_/', $current_route) ||
            preg_match('/^conversations_/', $current_route) ||
            preg_match('/^planning/', $current_route)) {{ 'encours' }} @endif @if ($left_bar_tools == 'closed') {{ 'submenu-closed' }} @endif">
                <span class="line tools" title="{{ __('projectsquare::left_bar.tools') }}" data-id="tools">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">{{ __('projectsquare::left_bar.tools') }}</h3>
                    <span class="toggle-childs"></span>
                </span>
                <ul class="sub-menu">
                    <li class="@if (preg_match('/tasks_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('tasks_index') }}">{{ __('projectsquare::left_bar.tasks') }}</a>
                    </li>

                    <li class="@if (preg_match('/tickets_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('tickets_index') }}">{{ __('projectsquare::left_bar.tickets') }}</a>
                    </li>

                    <li class="@if (preg_match('/conversations_/', $current_route)) {{ 'encours' }} @endif">
                        <a href="{{ route('conversations_index') }}">{{ __('projectsquare::left_bar.messages') }}</a>
                    </li>

                    <li class="@if (preg_match('/planning/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('planning') }}">{{ __('projectsquare::left_bar.planning') }}</a></li>
                </ul>
            </li>

            @if ($is_admin)
                <li class="menu @if (preg_match('/clients_/', $current_route) ||
                 preg_match('/projects_/', $current_route) ||
                 preg_match('/occupation/', $current_route) ||
                 preg_match('/^spent_time/', $current_route) ||
                 preg_match('/^progress/', $current_route)) {{ 'encours' }} @endif @if ($left_bar_management == 'closed') {{ 'submenu-closed' }} @endif">
                    <span class="line management" title="{{ __('projectsquare::left_bar.management') }}" data-id="management">
                        <span class="border"><span class="icon"></span></span>
                        <h3 class="title">{{ __('projectsquare::left_bar.management') }}</h3>
                        <span class="toggle-childs"></span>
                    </span>
                    <ul class="sub-menu">
                        <li class="@if (preg_match('/^progress/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('progress') }}">{{ __('projectsquare::left_bar.progress') }}</a></li>
                        <li class="@if (preg_match('/^spent_time/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('spent_time') }}">{{ __('projectsquare::left_bar.spent_time') }}</a></li>
                        <li class="@if (preg_match('/occupation/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('occupation') }}">{{ __('projectsquare::left_bar.occupation') }}</a></li>
                        <li class="@if (preg_match('/clients_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('clients_index') }}">{{ __('projectsquare::left_bar.clients') }}</a></li>
                        <li class="@if (preg_match('/projects_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('projects_index') }}">{{ __('projectsquare::left_bar.projects') }}</a></li>
                    </ul>
                </li>
            @endif

            @if ($is_admin)
                <li class="menu @if (preg_match('/roles_/', $current_route) ||
                 preg_match('/ticket_types_/', $current_route) ||
                 preg_match('/ticket_statuses_/', $current_route) ||
                 preg_match('/users_/', $current_route) ||
                 preg_match('/settings/', $current_route)) {{ 'encours' }} @endif @if ($left_bar_administration == 'closed') {{ 'submenu-closed' }} @endif">
                    <span class="line administration" title="{{ __('projectsquare::left_bar.administration') }}" data-id="administration">
                        <span class="border"><span class="icon"></span></span>
                        <h3 class="title">{{ __('projectsquare::left_bar.administration') }}</h3>
                        <span class="toggle-childs"></span>
                    </span>
                    <ul class="sub-menu">
                        <li class="@if (preg_match('/users_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('users_index') }}">{{ __('projectsquare::left_bar.users') }}</a></li>
                        <li class="@if (preg_match('/roles_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('roles_index') }}">{{ __('projectsquare::left_bar.profils') }}</a></li>
                        <li class="@if (preg_match('/ticket_types_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('ticket_types_index') }}">{{ __('projectsquare::left_bar.tickets_types') }}</a></li>
                        <li class="@if (preg_match('/ticket_statuses_/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('ticket_statuses_index') }}">{{ __('projectsquare::left_bar.tickets_statuses') }}</a></li>
                        <li class="@if (preg_match('/settings/', $current_route)) {{ 'encours' }} @endif"><a href="{{ route('settings_index') }}">{{ __('projectsquare::left_bar.settings') }}</a></li>
                    </ul>
                </li>
            @endif


            <li class="menu">
                <span class="line beta-form">
                    <span class="border"><span class="icon"></span></span>
                    <h3 class="title">Contacter le support</h3>
                </span>
            </li>

            <li class="menu">
                <span class="line toggle-left-bar" title="{{ __('projectsquare::left_bar.fold_left_bar') }}">
                    <span class="border">
                        <span class="icon glyphicon glyphicon-triangle-@if ($left_bar == 'closed'){{'right'}}@else{{'left'}}@endif"></span>
                    </span>
                    <h3 class="title">{{ __('projectsquare::left_bar.fold_left_bar') }}</h3>
                </span>
            </li>
        @endif
    </ul>
</nav>
