@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('tickets_index') }}">{{ trans('gateway::tickets.tickets_list') }}</a></li>
        <li class="active">{{ trans('gateway::tickets.edit_ticket') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::tickets.edit_ticket') }}</h1>
    </div>

    @if (isset($error))
        <div class="info bg-danger">
            {{ $error }}
        </div>
    @endif

    @if (isset($confirmation))
        <div class="info bg-success">
            {{ $confirmation }}
        </div>
    @endif

    @include('gateway::tickets.form', [
        'form_action' => route('tickets_update'),
        'ticket_id' => $ticket->id,
        'ticket_title' => $ticket->title,
        'ticket_description' => $ticket->description,
        'ticket_due_date' => $ticket->due_date
    ])

    <h2>{{ trans('gateway::tickets.ticket_history') }}</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <th>#</th>
            <th>{{ trans('gateway::generic.creation_date') }}</th>
            <th>{{ trans('gateway::tickets.author_user') }}</th>
            <th>{{ trans('gateway::tickets.allocated_user') }}</th>
            <th>{{ trans('gateway::tickets.status') }}</th>
            <th>{{ trans('gateway::tickets.priority') }}</th>
        </thead>
        <tbody>
            @foreach ($ticket->states as $i => $ticket_state)
                <tr>
                    <td>{{ $ticket_state->id }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($ticket_state->created_at)) }}</td>
                    <td>@if ($ticket_state->author_user){{ $ticket_state->author_user->first_name }} {{ $ticket_state->author_user->last_name }}@endif</td>
                    <td>@if ($ticket_state->allocated_user){{ $ticket_state->allocated_user->first_name }} {{ $ticket_state->allocated_user->last_name }}@endif</td>
                    <td>@if ($ticket_state->status)<span class="label label-primary">{{ $ticket_state->status->name }}</span>@endif</td>
                    <td><span class="badge priority{{ $ticket_state->priority }}">{{ $ticket_state->priority }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection