<div class="project-bar">
    <div class="project-name" style="background: {{ $project->color }}">
        {{ $project->name }}
        <i class="hamburger"></i>
    </div>
    <nav>
        <ul class="tabs">
            @if (!$is_client)
                <li @if ($active == 'tasks')class="current"@endif><a href="{{ route('project_tasks', ['id' => $project->id]) }}">{{ trans('projectsquare::project.tasks') }}</a></li>
            @endif

            <li @if ($active == 'tickets')class="current"@endif><a href="{{ route('project_tickets', ['id' => $project->id]) }}">{{ trans('projectsquare::project.tickets') }}</a></li>

             <li @if ($active == 'cms')class="current"@endif><a href="{{ route('project_cms', ['id' => $project->id]) }}">{{ trans('projectsquare::project.cms') }}</a></li>

            @if (!$is_client)
                <li @if ($active == 'monitoring')class="current"@endif><a href="{{ route('project_monitoring', ['id' => $project->id]) }}">{{ trans('projectsquare::project.monitoring') }}</a></li>
            @endif

            <li @if ($active == 'seo')class="current"@endif><a href="{{ route('project_seo', ['id' => $project->id]) }}">{{ trans('projectsquare::project.seo') }}</a></li>
            <li @if ($active == 'messages')class="current"@endif><a href="{{ route('project_messages', ['id' => $project->id]) }}">{{ trans('projectsquare::project.messages') }}</a></li>
            <li @if ($active == 'files')class="current"@endif><a href="{{ route('project_files', ['id' => $project->id]) }}">{{ trans('projectsquare::project.files') }}</a></li>

            @if ($is_admin)
                <li @if ($active == 'progress')class="current"@endif><a href="{{ route('project_progress', ['id' => $project->id]) }}">{{ trans('projectsquare::project.progress') }}</a></li>
            @endif

            @if ($is_admin)
                <li @if ($active == 'spent_time')class="current"@endif><a href="{{ route('project_spent_time', ['id' => $project->id]) }}">{{ trans('projectsquare::project.spent_time') }}</a></li>
            @endif
            <li class="border-bottom"></li>
        </ul>
    </nav>
</div>