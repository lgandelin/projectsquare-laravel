@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('projectsquare::agency.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::ticket_statuses.ticket_statuses_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::ticket_statuses.ticket_statuses_list') }}</h1>
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
                <th>{{ trans('projectsquare::ticket_statuses.ticket_status') }}</th>
                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($ticket_statuses as $ticket_status)
                <tr>
                    <td>{{ $ticket_status->id }}</td>
                    <td>{{ $ticket_status->name }}</td>
                    <td>
                        <a href="{{ route('ticket_statuses_edit', ['id' => $ticket_status->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                        <a href="{{ route('ticket_statuses_delete', ['id' => $ticket_status->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $ticket_statuses->render() !!}
    </div>

    <a href="{{ route('ticket_statuses_add') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('projectsquare::ticket_statuses.add_ticket_status') }}</a>
@endsection