Une tâche a été modifiée :<br/><br/>

<strong>{{ $task->title }}</strong><br/><br/>

@if (isset($task->project) && isset($task->project->client))<strong>Projet :</strong> [{{ $task->project->client->name }}] {{ $task->project->name }}@endif<br/><br/>
@if (isset($task->status))<strong>Etat :</strong> @if ($task->status == 1) A faire @elseif ($task->status == 2) En cours @else Terminé @endif<br/><br/>@endif
@if ($task->description)<strong>Description :</strong> {!! $task->description !!}<br/><br/>@endif

<br/>
<a href="{{ route('tasks_edit', ['uuid' => $task->id]) }}">Cliquez ici pour voir la tâche</a>

<br/><br/><br/>
L'équipe Projectsquare