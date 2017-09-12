<div class="middle-column">

    <div class="parent">
        <div class="parent-wrapper create">
            <a href="{{ route('tickets_add') }}"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ trans('projectsquare::tickets.add_ticket') }}</a>
        </div>
    </div>

    @if (sizeof($tickets_grouped_by_states) > 0)
        @foreach ($tickets_grouped_by_states as $state)
            <div class="parent">
                <div class="parent-wrapper">
                    <span class="name">{{ $state->name }}</span>
                    <span class="childs-number">{{ sizeof($state->tickets) }}</span>
                    <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
                </div>

                <div class="childs">
                    @if ($state->tickets)
                        @foreach ($state->tickets as $ticket)
                            <div class="child @if (isset($currentTicketID) && $ticket->id == $currentTicketID)current @endif">
                                <div class="child-wrapper">
                                    <a href="{{ route('project_tickets_edit', ['uuid' => $project->id, 'ticket_uuid' => $ticket->id]) }}">

                                        @if (isset($ticket->last_state) && $ticket->last_state->allocated_user)
                                            @include('projectsquare::includes.avatar', [
                                                'id' => $ticket->last_state->allocated_user->id,
                                                'name' => $ticket->last_state->allocated_user->first_name . ' ' . $ticket->last_state->allocated_user->last_name
                                            ])
                                        @else
                                            <img class="avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                                        @endif
                                        <span class="name">{{ $ticket->title }}</span>

                                        @if (isset($ticket->last_state) && isset($ticket->last_state->priority))
                                            <span class="priority priority-{{ $ticket->last_state->priority }}" title="{{ trans('projectsquare::generic.priority-' . $ticket->last_state->priority) }}"></span>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>