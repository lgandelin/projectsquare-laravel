@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li class="active">{{ trans('gateway::ticket_types.ticket_types_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::ticket_types.ticket_types_list') }}</h1>
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
                <th>{{ trans('gateway::ticket_types.ticket_type') }}</th>
                <th>{{ trans('gateway::generic.action') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($ticket_types as $ticket_type)
                <tr>
                    <td>{{ $ticket_type->id }}</td>
                    <td>{{ $ticket_type->name }}</td>
                    <td>
                        <a href="{{ route('ticket_types_edit', ['id' => $ticket_type->id]) }}" class="btn btn-primary">{{ trans('gateway::generic.edit') }}</a>
                        <a href="{{ route('ticket_types_delete', ['id' => $ticket_type->id]) }}" class="btn btn-danger">{{ trans('gateway::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('ticket_types_add') }}" class="btn btn-success">{{ trans('gateway::ticket_types.add_ticket_type') }}</a>
@endsection