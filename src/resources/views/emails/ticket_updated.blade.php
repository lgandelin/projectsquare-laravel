Un ticket a été modifié :<br/><br/><br/>

<strong>Titre :</strong> {{ $ticket->title }}<br/><br/>
@if (isset($ticket->project) && isset($ticket->project->client))<strong>Projet :</strong> [{{ $ticket->project->client->name }}] {{ $ticket->project->name }}@endif<br/><br/>
@if ($ticket->states[0]->status->id != $ticket->states[1]->status->id)<strong>Etat :</strong> {{ $ticket->last_state->status->name }}<br/><br/>@endif
@if ($ticket->last_state->priority != $ticket->states[1]->priority)<strong>Priorité :</strong> {{ __('projectsquare::generic.priority-' . $ticket->last_state->priority) }}<br/><br/>@endif
@if ($ticket->last_state->due_date != $ticket->states[1]->dueDate)<strong>Echéance :</strong> {{ $ticket->last_state->due_date }}<br/><br/>@endif
@if ($ticket->last_state->comments)<strong>Commentaires :</strong> {{ nl2br($ticket->last_state->comments) }}<br/><br/>@endif

<br/>
<a href="{{ route('tickets_edit', ['uuid' => $ticket->id]) }}">Cliquez ici pour voir le ticket</a>

<br/><br/><br/>
L'équipe Projectsquare