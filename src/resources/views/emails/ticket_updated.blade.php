Un ticket a été modifié @if (isset($ticket->last_state->author_user))par {{ $ticket->last_state->author_user->firstName }} {{ $ticket->last_state->author_user->lastName }}@endif :<br/><br/>

<strong>{{ $ticket->title }}</strong><br/><br/>

@if (isset($ticket->project) && isset($ticket->project->client))<strong>Projet :</strong> [{{ $ticket->project->client->name }}] {{ $ticket->project->name }}@endif<br/><br/>
@if ($ticket->states[0]->status->id != $ticket->states[1]->status->id)<strong>Etat :</strong> {{ $ticket->last_state->status->name }}<br/><br/>@endif
@if ($ticket->last_state->priority != $ticket->states[1]->priority)<strong>Priorité :</strong> {{ trans('projectsquare::generic.priority-' . $ticket->last_state->priority) }}<br/><br/>@endif
@if ($ticket->last_state->due_date != $ticket->states[1]->dueDate)<strong>Echéance :</strong> {{ $ticket->last_state->due_date }}<br/><br/>@endif
@if ($ticket->last_state->comments)<strong>Commentaires :</strong> {{ $ticket->last_state->comments }}<br/><br/>@endif

<br/>
<a href="{{ route('tickets_edit', ['uuid' => $ticket->id]) }}">Cliquez ici pour voir le ticket</a>

<br/><br/><br/>
L'équipe Projectsquare