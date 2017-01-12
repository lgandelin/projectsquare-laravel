<div class="project-bar">
    <nav>
        <ul class="nav nav-pills top-nav">
            @if (!$is_client)
                <li @if ($active == 'index')class="active"@endif><a href="{{ route('project_index', ['id' => $project->id]) }}">{{ trans('projectsquare::project.cms') }}</a></li>
            @else
                <li @if ($active == 'dashboard')class="active"@endif><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            @endif
            @if (!$is_client)
            <li @if ($active == 'tasks')class="active"@endif><a href="{{ route('project_tasks', ['id' => $project->id]) }}">{{ trans('projectsquare::project.tasks') }}</a></li>
            @endif
            @if (!$is_client)<li @if ($active == 'monitoring')class="active"@endif><a href="{{ route('project_monitoring', ['id' => $project->id]) }}">{{ trans('projectsquare::project.monitoring') }}</a></li>@endif
            <li @if ($active == 'calendar')class="active"@endif><a href="{{ route('project_calendar', ['id' => $project->id]) }}">{{ trans('projectsquare::project.calendar') }}</a></li>
            <li @if ($active == 'seo')class="active"@endif><a href="{{ route('project_seo', ['id' => $project->id]) }}">{{ trans('projectsquare::project.seo') }}</a></li>
            <li @if ($active == 'tickets')class="active"@endif><a href="{{ route('project_tickets', ['id' => $project->id]) }}">{{ trans('projectsquare::project.tickets') }}</a></li>
            <li @if ($active == 'messages')class="active"@endif><a href="{{ route('project_messages', ['id' => $project->id]) }}">{{ trans('projectsquare::project.messages') }}</a></li>
            <li @if ($active == 'files')class="active"@endif><a href="{{ route('project_files', ['id' => $project->id]) }}">{{ trans('projectsquare::project.files') }}</a></li>
            @if (!$is_client)<li @if ($active == 'reporting')class="active"@endif><a href="{{ route('project_reporting', ['id' => $project->id]) }}">{{ trans('projectsquare::project.reporting') }}</a></li>@endif
        </ul>
    </nav>
</div>