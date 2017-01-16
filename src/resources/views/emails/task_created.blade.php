Une nouvelle tâche vous a été assignée :<br/>

<strong>{{ $task->title }}</strong>

@if (isset($task->project) && isset($task->project->client))<strong>Projet :</strong> [{{ $task->project->client->name }}] {{ $task->project->name }}@endif<br/><br/>
@if (isset($task->status))<strong>Etat :</strong> @if ($task->status == 1) A faire @elseif ($task->status == 2) En cours @else Terminé @endif<br/><br/>@endif
@if (isset($task->last_state->author_user))<strong>Auteur :</strong> {{ $task->last_state->author_user->complete_name }}<br/><br/>@endif
@if ($task->description)<strong>Description :</strong> {!! $task->description !!}<br/><br/>@endif
