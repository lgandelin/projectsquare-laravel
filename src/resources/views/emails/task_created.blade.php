Une nouvelle tâche vous a été assignée :<br/><br/><br/>

<strong>Titre :</strong> {{ $task->title }}<br/><br/>
@if (isset($task->project) && isset($task->project->client))<strong>Projet :</strong> [{{ $task->project->client->name }}] {{ $task->project->name }}@endif<br/><br/>
@if (isset($task->status_id))<strong>Etat :</strong> @if ($task->status_id == 1) A faire @elseif ($task->status_id == 2) En cours @else Terminé @endif<br/><br/>@endif
@if ($task->description)<strong>Description :</strong> {!! nl2br($task->description) !!}<br/><br/>@endif

<br/>
<a href="{{ route('tasks_edit', ['uuid' => $task->id]) }}">Cliquez ici pour voir la tâche</a>

<br/><br/><br/>
L'équipe Projectsquare