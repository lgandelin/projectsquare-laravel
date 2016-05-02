<div class="table-responsive ticket-history">
    <table class="table table-striped table-bordered">
        <thead>
        <th>{{ trans('projectsquare::generic.creation_date') }}</th>
        <th>{{ trans('projectsquare::tickets.author_user') }}</th>
        <th>{{ trans('projectsquare::tickets.allocated_user') }}</th>
        <th>{{ trans('projectsquare::tickets.due_date') }}</th>
        <th>{{ trans('projectsquare::tickets.estimated_time') }}</th>
        <th>{{ trans('projectsquare::tickets.priority') }}</th>
        <th>{{ trans('projectsquare::tickets.status') }}</th>
        <th>{{ trans('projectsquare::tickets.comments') }}</th>
        </thead>
        <tbody>
            @foreach ($ticket_states as $i => $ticket_state)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($ticket_state->created_at)) }}</td>
                <td>@if ($ticket_state->author_user){{ $ticket_state->author_user->complete_name }}@endif</td>
                <td>@if ($ticket_state->allocated_user){{ $ticket_state->allocated_user->complete_name }}@endif</td>
                <td>@if ($ticket_state->due_date){{ $ticket_state->due_date }}@endif</td>
                <td>@if ($ticket_state->estimated_time){{ $ticket_state->estimated_time }}@endif</td>
                <td><span class="badge priority-{{ $ticket_state->priority }}">{{ $ticket_state->priority }}</span></td>
                <td>@if ($ticket_state->status)<span class="status status-{{ $ticket_state->status->id }}">{{ $ticket_state->status->name }}</span>@endif</td>
                <td>{{ $ticket_state->comments }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="text-center">
    {!! $ticket_states->render() !!}
</div>