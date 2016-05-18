<div class="block">
    <h3>{{ trans('projectsquare::dashboard.last_tickets') }}</h3>
    <div class="block-content table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                <th>{{ trans('projectsquare::tickets.client') }} / {{ trans('projectsquare::tickets.project') }}</th>
                <th>{{ trans('projectsquare::tickets.type') }}</th>
                <th>{{ trans('projectsquare::tickets.allocated_user') }}</th>
                <th>{{ trans('projectsquare::tickets.status') }}</th>
                <th>{{ trans('projectsquare::tickets.priority') }}</th>
                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td><a href="{{ route('project_index', ['id' => $ticket->project->id]) }}"><span class="label" style="background: {{ $ticket->project->color }}">{{ $ticket->project->client->name }}</span> {{ $ticket->project->name }}</a></td>
                    <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                    <td>@if (isset($ticket->last_state) && $ticket->last_state->allocated_user){{ $ticket->last_state->allocated_user->complete_name }}@endif</td>
                    <td width="10%">@if (isset($ticket->last_state) && isset($ticket->last_state->status))<span class="status status-{{ $ticket->last_state->status->id }}">{{ $ticket->last_state->status->name }}</span>@endif</td>
                    <td>@if (isset($ticket->last_state))<span class="badge priority-{{ $ticket->last_state->priority }}">{{ $ticket->last_state->priority }}</span>@endif</td>
                    <td>
                        <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('projectsquare::tickets.see_ticket') }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a href="{{ route('tickets_add') }}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::tickets.add_ticket') }}</a>
        <a href="{{ route('tickets_index') }}" class="btn btn-sm btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('projectsquare::tickets.see_tickets') }}</a>
    </div>
</div>