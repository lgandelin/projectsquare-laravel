<div class="table-responsive ticket-history">
    <table class="table table-striped table-bordered">
        <thead>
        <th>{{ __('projectsquare::generic.creation_date') }}</th>
        <th>{{ __('projectsquare::tickets.author_user') }}</th>
        <th>{{ __('projectsquare::tickets.allocated_user') }}</th>
        <th>{{ __('projectsquare::tickets.due_date') }}</th>
        <th>{{ __('projectsquare::tickets.estimated_time') }}</th>
        <th>{{ __('projectsquare::tickets.spent_time') }}</th>
        <th>{{ __('projectsquare::tickets.priority') }}</th>
        <th>{{ __('projectsquare::tickets.status') }}</th>
        <th>{{ __('projectsquare::tickets.comments') }}</th>
        </thead>
        <tbody>
            @foreach ($ticket_states as $i => $ticket_state)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($ticket_state->created_at)) }}</td>
                <td align="center">
                    @if ($ticket_state->author_user)
                        @if (isset($ticket_state) && $ticket_state->author_user)
                            @include('projectsquare::includes.avatar', [
                                'id' => $ticket_state->author_user->id,
                                'name' => $ticket_state->author_user->complete_name
                            ])
                        @endif
                    @endif
                </td>
                <td align="center">
                    @if ($ticket_state->allocated_user)
                        @if (isset($ticket_state) && $ticket_state->allocated_user)
                            @include('projectsquare::includes.avatar', [
                                'id' => $ticket_state->allocated_user->id,
                                'name' => $ticket_state->allocated_user->complete_name
                            ])
                        @endif
                    @endif
                </td>
                <td>@if ($ticket_state->due_date){{ $ticket_state->due_date }}@endif</td>
                <td>@if ($ticket_state->estimated_time_days > 0){{ $ticket_state->estimated_time_days }} {{ __('projectsquare::generic.days') }}@endif @if ($ticket_state->estimated_time_hours > 0){{ $ticket_state->estimated_time_hours }} {{ __('projectsquare::generic.hours') }}@endif</td>
                <td>@if ($ticket_state->spent_time_days > 0){{ $ticket_state->spent_time_days }} {{ __('projectsquare::generic.days') }}@endif @if ($ticket_state->spent_time_hours > 0){{ $ticket_state->spent_time_hours }} {{ __('projectsquare::generic.hours') }}@endif</td>
                <td><span class="priority priority-{{ $ticket_state->priority }}"></span></td>
                <td>@if ($ticket_state->status)<span class="status status-{{ $ticket_state->status->id }}">{{ $ticket_state->status->name }}</span>@endif</td>
                <td>{!! nl2br($ticket_state->comments) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="text-center">
    {!! $ticket_states->render() !!}
</div>