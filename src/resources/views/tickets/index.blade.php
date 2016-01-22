@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('gateway::tickets.tickets_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::tickets.tickets_list') }}</h1>
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

    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('gateway::tickets.ticket') }}</th>
            <th>{{ trans('gateway::tickets.client') }}</th>
            <th>{{ trans('gateway::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->name }}</td>
            <td>{{ $ticket->client->name }}</td>
            <td>
                <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary">{{ trans('gateway::generic.edit') }}</a>
                <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn btn-danger">{{ trans('gateway::generic.delete') }}</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('tickets_add') }}" class="btn btn-success">{{ trans('gateway::tickets.add_ticket') }}</a>
@endsection