<div class="project-bar">
    <nav>
        <ul class="nav nav-pills">
            <li @if ($active == 'index')class="active"@endif><a href="{{ route('project_index', ['id' => $project->id]) }}">{{ trans('gateway::project.summary') }}</a></li>
            <li @if ($active == 'cms')class="active"@endif><a href="{{ route('project_cms', ['id' => $project->id]) }}">{{ trans('gateway::project.cms') }}</a></li>
            <li><a>Référencement</a></li>
            <li><a>Tickets</a></li>
            <li><a>Planning</a></li>
            <li><a>Monitoring</a></li>
            <li><a>Messages</a></li>
        </ul>
    </nav>
</div>