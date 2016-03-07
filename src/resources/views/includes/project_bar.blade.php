<div class="project-bar">
    <nav>
        <ul class="nav nav-pills">
            <li @if ($active == 'index')class="active"@endif><a href="{{ route('project_index', ['id' => $project->id]) }}">{{ trans('projectsquare::project.summary') }}</a></li>
            <li @if ($active == 'cms')class="active"@endif><a href="{{ route('project_cms', ['id' => $project->id]) }}">{{ trans('projectsquare::project.cms') }}</a></li>
            <li @if ($active == 'tickets')class="active"@endif><a href="{{ route('project_tickets', ['id' => $project->id]) }}">{{ trans('projectsquare::project.tickets') }}</a></li>
            <li @if ($active == 'monitoring')class="active"@endif><a href="{{ route('project_monitoring', ['id' => $project->id]) }}">{{ trans('projectsquare::project.monitoring') }}</a></li>
            <li><a>Référencement</a></li>
            <li><a>Planning</a></li>
            <li @if ($active == 'messages')class="active"@endif><a href="{{ route('project_messages', ['id' => $project->id]) }}">{{ trans('projectsquare::project.messages') }}</a></li>
            <li @if ($active == 'settings')class="active"@endif><a href="{{ route('project_settings', ['id' => $project->id]) }}">{{ trans('projectsquare::project.settings') }}</a></li>
        </ul>
    </nav>
</div>