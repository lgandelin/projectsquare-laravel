@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('projectsquare::agency.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::clients.clients_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::clients.clients_list') }}</h1>
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
            <th>{{ trans('projectsquare::clients.client') }}</th>
            <th>{{ trans('projectsquare::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
            @foreach ($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>
                        <a href="{{ route('clients_edit', ['id' => $client->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                        <a href="{{ route('clients_delete', ['id' => $client->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $clients->render() !!}
    </div>

    <a href="{{ route('clients_add') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('projectsquare::clients.add_client') }}</a>
@endsection