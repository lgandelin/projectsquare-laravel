<div class="block last-tickets">
    <div class="block-content table-responsive">
        <h3>{{ trans('projectsquare::dashboard.last_tickets') }}
        
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.tickets')
            ])
            <a href="{{ route('project_tickets', ['id' => $current_project->id]) }}" class="all pull-right" title="{{ trans('projectsquare::dashboard.tickets_list') }}"></a>
            <a href="{{ route('tickets_add') }}" class="add pull-right" title="{{ trans('projectsquare::dashboard.add_ticket') }}"></a>
            <a href="#" class="glyphicon glyphicon-move move-widget pull-right" title="{{ trans('projectsquare::dashboard.move_widget') }}"></a>
        </h3>
     
        <table class="table table-striped">
            <thead>
            <tr>
                <th></th>
                <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                <th style="text-align: center;">{{ trans('projectsquare::tickets.priority') }}</th>
                <th>{{ trans('projectsquare::tickets.type') }}</th>
                <th>{{ trans('projectsquare::tickets.status') }}</th>
                <th>{{ trans('projectsquare::tickets.allocated_user') }}</th>
                
                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td style="border-left: 10px solid {{ $ticket->project->color }}"></td>
                    <td class="entity_title"><a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}">{{ $ticket->title }}</a></td>
                    <td align="center">@if (isset($ticket->last_state))<span class="priority priority-{{ $ticket->last_state->priority }}" title="{{ trans('projectsquare::generic.priority-' . $ticket->last_state->priority) }}"></span>@endif</td>
                    <td>@if (isset($ticket->type)){{ $ticket->type->name }}@endif</td>
                    <td width="10%">@if (isset($ticket->last_state) && isset($ticket->last_state->status))<span class=" text status">{{ $ticket->last_state->status->name }}</span>@endif</td>
                    <td>
                        @if (isset($ticket->last_state) && $ticket->last_state->allocated_user)
                            @include('projectsquare::includes.avatar', [
                                'id' => $ticket->last_state->allocated_user->id,
                                'name' => $ticket->last_state->allocated_user->complete_name
                            ])
                        @endif
                    </td>
                    <td class="action">
                        <a href="{{ route('project_tickets_edit', ['uuid' => $ticket->project_id, 'ticket_uuid' => $ticket->id]) }}" class="btn btn-sm btn-primary see-more" title="{{ trans('projectsquare::dashboard.see_ticket') }}"></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
     
    </div>
</div>
