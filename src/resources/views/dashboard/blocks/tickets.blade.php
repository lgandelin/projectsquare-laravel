<div class="block last-tickets">
    <div class="block-content table-responsive">
        <h3>{{ __('projectsquare::dashboard.last_tickets') }}

            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.tickets')
            ])

            @if ($is_client)
                <a href="{{ route('project_tickets', ['id' => $current_project->id]) }}" class="all pull-right" title="{{ __('projectsquare::dashboard.tickets_list') }}"></a>
            @else
                <a href="{{ route('tickets_index') }}" class="all pull-right" title="{{ __('projectsquare::dashboard.tickets_list') }}"></a>
            @endif

            <a href="{{ route('tickets_add') }}" class="add pull-right" title="{{ __('projectsquare::dashboard.add_ticket') }}"></a>
            <a href="#" class="glyphicon glyphicon-move move-widget pull-right" title="{{ __('projectsquare::dashboard.move_widget') }}"></a>
        </h3>

        <table class="table table-striped">
            <thead>
            <tr>
                <th></th>
                <th>{{ __('projectsquare::tickets.ticket') }}</th>
                <th style="text-align: center;">{{ __('projectsquare::tickets.priority') }}</th>
                <th>{{ __('projectsquare::tickets.type') }}</th>
                <th>{{ __('projectsquare::tickets.status') }}</th>
                <th>{{ __('projectsquare::tickets.allocated_user') }}</th>

                <th>{{ __('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td class="project-border" style="border-left: 10px solid {{ $ticket->project->color }}"></td>
                    <td>
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}">
                            {{ $ticket->title }}
                        </a>
                    </td>
                    <td align="center">
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}">
                            @if (isset($ticket->last_state))<span class="priority priority-{{ $ticket->last_state->priority }}" title="{{ __('projectsquare::generic.priority-' . $ticket->last_state->priority) }}"></span>@endif
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}">
                            @if (isset($ticket->type)){{ $ticket->type->name }}@endif
                        </a>
                    </td>
                    <td width="10%">
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}">
                            @if (isset($ticket->last_state) && isset($ticket->last_state->status))<span class=" text status">{{ $ticket->last_state->status->name }}</span>@endif
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}">
                            @if (isset($ticket->last_state) && $ticket->last_state->allocated_user)
                                @include('projectsquare::includes.avatar', [
                                    'id' => $ticket->last_state->allocated_user->id,
                                    'name' => $ticket->last_state->allocated_user->complete_name
                                ])
                            @else
                                <img class="default-avatar avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                            @endif
                        </a>
                    </td>
                    <td align="right">
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}" title="{{ __('projectsquare::dashboard.see_ticket') }}">
                            <i class="btn btn-sm btn-primary see-more"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
