<table class="table table-striped table-bordered">
    <thead>
    <th>{{ trans('gateway::generic.creation_date') }}</th>
    <th>{{ trans('gateway::tickets.author_user') }}</th>
    <th>{{ trans('gateway::tickets.allocated_user') }}</th>
    <th>{{ trans('gateway::tickets.due_date') }}</th>
    <th>{{ trans('gateway::tickets.priority') }}</th>
    <th>{{ trans('gateway::tickets.status') }}</th>
    <th>{{ trans('gateway::tickets.comments') }}</th>
    </thead>
    <tbody>
    @foreach ($ticket_states as $i => $ticket_state)
    <tr>
        <td>{{ date('d/m/Y H:i', strtotime($ticket_state->created_at)) }}</td>
        <td>@if ($ticket_state->author_user){{ $ticket_state->author_user->complete_name }}@endif</td>
        <td>@if ($ticket_state->allocated_user){{ $ticket_state->allocated_user->complete_name }}@endif</td>
        <td>@if ($ticket_state->due_date){{ $ticket_state->due_date }}</span>@endif</td>
        <td><span class="badge priority-{{ $ticket_state->priority }}">{{ $ticket_state->priority }}</span></td>
        <td>@if ($ticket_state->status)<span class="status status-{{ $ticket_state->status->id }}">{{ $ticket_state->status->name }}</span>@endif</td>
        <td>{{ $ticket_state->comments }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="text-center">
    {!! $ticket_states->render() !!}
</div>