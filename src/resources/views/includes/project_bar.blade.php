<div class="project-bar">
    <nav>
        <ul class="nav nav-pills">
            <li @if ($active == 'index')class="active"@endif><a href="{{ route('project_index', ['id' => $project->id]) }}">{{ trans('gateway::project.summary') }}</a></li>
            <li @if ($active == 'cms')class="active"@endif><a href="{{ route('project_cms', ['id' => $project->id]) }}">{{ trans('gateway::project.cms') }}</a></li>
            <li @if ($active == 'tickets')class="active"@endif><a href="{{ route('project_tickets', ['id' => $project->id]) }}">{{ trans('gateway::project.tickets') }}</a></li>
            <li @if ($active == 'monitoring')class="active"@endif><a href="{{ route('project_monitoring', ['id' => $project->id]) }}">Monitoring</a></li>
            <li><a>Référencement</a></li>
            <li><a>Planning</a></li>
            <li><a>Messages</a></li>
        </ul>
    </nav>
</div>