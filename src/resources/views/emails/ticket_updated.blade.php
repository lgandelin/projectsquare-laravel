Un ticket a été modifié @if (isset($author_user))par {{ $author_user->firstName }} {{ $author_user->lastName }}@endif :<br/><br/>

<strong>{{ $ticket->title }}</strong><br/><br/>

@if (isset($project) && isset($project->client))<strong>Projet :</strong> [{{ $project->client->name }}] {{ $project->name }}@endif<br/><br/>
@if ($ticket->states[0]->statusID != $ticket->states[1]->statusID)<strong>Etat :</strong> {{ $new_status->name }}<br/><br/>@endif
{{--@if (isset($ticket->last_state->author_user))<strong>Auteur :</strong> {{ $ticket->last_state->author_user->complete_name }}<br/><br/>@endif--}}
@if ($ticket->states[0]->priority != $ticket->states[1]->priority)<strong>Priorité :</strong> {{ trans('projectsquare::generic.priority-' . $ticket->states[0]->priority) }}<br/><br/>@endif
@if ($ticket->states[0]->dueDate != $ticket->states[1]->dueDate)<strong>Echéance :</strong> {{ $ticket->states[0]->dueDate }}<br/><br/>@endif
@if ($ticket->states[0]->comments)<strong>Commentaires :</strong> {{ $ticket->last_state->comments }}<br/><br/>@endif

<br/>
<a href="{{ route('tickets_edit', ['uuid' => $ticket->id]) }}">Cliquez ici pour voir le ticket</a>

<br/><br/><br/>
L'équipe Projectsquare