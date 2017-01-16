Un nouveau ticket vous a été assigné :<br/>

<strong>{{ $ticket->title }}</strong>

@if (isset($ticket->project) && isset($ticket->project->client))<strong>Projet :</strong> [{{ $ticket->project->client->name }}] {{ $ticket->project->name }}@endif<br/><br/>
@if (isset($ticket->type))<strong>Type:</strong> {{ $ticket->type->name }}<br/><br/>@endif
@if (isset($ticket->last_state->status))<strong>Etat :</strong> {{ $ticket->last_state->status->name }}<br/><br/>@endif
@if (isset($ticket->last_state->author_user))<strong>Auteur :</strong> {{ $ticket->last_state->author_user->complete_name }}<br/><br/>@endif
@if ($ticket->description)<strong>Description :</strong> {!! $ticket->description !!}<br/><br/>@endif
@if ($ticket->last_state->priority)<strong>Priorité :</strong> {{ $ticket->last_state->priority}}<br/><br/>@endif
@if ($ticket->last_state->due_date)<strong>Echéance :</strong> {{ $ticket->last_state->due_date }}<br/><br/>@endif
@if ($ticket->last_state->comments)<strong>Commentaires :</strong> {{ $ticket->last_state->comments }}@endif