<div class="project-bar">
    <nav>
        <ul class="nav nav-pills">
            <li class="active"><a href="{{ route('project_index', ['id' => $project->id]) }}">Résumé</a></li>
            <li><a href="{{ route('project_cms', ['id' => $project->id]) }}">CMS</a></li>
            <li><a href="#">Référencement</a></li>
            <li><a href="#">Tickets</a></li>
            <li><a href="#">Planning</a></li>
            <li><a href="#">Monitoring</a></li>
            <li><a href="#">Messages</a></li>
        </ul>
    </nav>
</div>