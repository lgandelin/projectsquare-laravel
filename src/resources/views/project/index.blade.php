@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar')

    <h1 class="page-header">{{ $project->client->name }} - {{ $project->name }}</h1>

    <div class="col-md-12">
        <h3>{{ trans('gateway::project.last_tickets') }}</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('gateway::tickets.ticket') }}</th>
                <th>{{ trans('gateway::tickets.author_user') }}</th>
                <th>{{ trans('gateway::tickets.allocated_user') }}</th>
                <th>{{ trans('gateway::tickets.type') }}</th>
                <th>{{ trans('gateway::tickets.priority') }}</th>
                <th>{{ trans('gateway::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td width="40%">{{ $ticket->title }}</td>
                <td>@if (isset($ticket->states[0])){{ $ticket->states[0]->author_user->complete_name }}@endif</td>
                <td>@if (isset($ticket->states[0])){{ $ticket->states[0]->allocated_user->complete_name }}@endif</td>
                <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                <td>@if (isset($ticket->states[0]))<span class="badge priority{{ $ticket->states[0]->priority }}">{{ $ticket->states[0]->priority }}</span>@endif</td>
                <td>
                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn-sm btn-primary">Voir le ticket</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <a href="{{ route('tickets_add') }}" class="btn btn-success">{{ trans('gateway::tickets.add_ticket') }}</a>
    </div>

@endsection