Une tâche a été supprimée :<br/><br/><br/>

<strong>Titre :</strong> {{ $task->title }}<br/><br/>
@if (isset($task->project) && isset($task->project->client))<strong>Projet :</strong> [{{ $task->project->client->name }}] {{ $task->project->name }}@endif<br/><br/>

<br/>
L'équipe Projectsquare