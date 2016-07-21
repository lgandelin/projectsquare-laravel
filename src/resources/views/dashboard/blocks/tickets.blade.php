<div class="block last-tickets">
    <div class="block-content table-responsive">
        <h3>{{ trans('projectsquare::dashboard.last_tickets') }}</h3>

        @if ($is_client)
            <a href="{{ route('project_tickets', ['id' => $current_project->id]) }}" class="all pull-right"></a>
        @else
            <a href="{{ route('tickets_index') }}" class="all pull-right"></a>
        @endif
        <a href="{{ route('tickets_add') }}" class="add pull-right"></a>

        <a href="#" class="glyphicon glyphicon-move move-widget pull-right"></a>
     
        <table class="table table-striped">
            <thead>
            <tr>
                <!--<th>#</th>-->    
                <th>{{ trans('projectsquare::tickets.priority') }}</th>
                <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                <!--<th>{{ trans('projectsquare::tickets.client') }} </th>--> 
                <th>{{ trans('projectsquare::tickets.type') }}</th>
                <th>{{ trans('projectsquare::tickets.status') }}</th>
                <th>{{ trans('projectsquare::tickets.allocated_user') }}</th>
                
                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <!-- <td>{{ $ticket->id }}</td> -->
                    <td class="priorities" style="border-left: 10px solid {{ $ticket->project->color }}">@if (isset($ticket->last_state))<span class="priority priority-{{ $ticket->last_state->priority }}"></span>@endif</td>
                    <td>{{ $ticket->title }}</td>
                   <!-- <td>{{ $ticket->project->client->name }}</td>--> 
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
                        <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-sm btn-primary see-more"></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
     
    </div>
</div>