@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('clients_index') }}">{{ trans('projectsquare::clients.clients_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::clients.edit_client') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::clients.edit_client') }}</h1>
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

    @include('projectsquare::agency.clients.form', [
        'form_action' => route('clients_update'),
        'client_id' => $client->id,
        'client_name' => $client->name,
        'client_address' => $client->address
    ])

    <br>

    <h2>Utilisateurs</h2>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('projectsquare::users.name') }}</th>
            <th>{{ trans('projectsquare::users.email') }}</th>
            <th>{{ trans('projectsquare::users.role') }}</th>
            <th>{{ trans('projectsquare::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->complete_name }}</td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>{{ $user->client_role }}</td>
                <td>
                    <a href="{{ route('clients_edit_user', ['client_id' => $client->id, 'user_id' => $user->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                    <a href="{{ route('clients_delete_user', ['client_id' => $client->id, 'user_id' => $user->id]) }}" class="btn btn-danger btn-delete"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('clients_add_user', ['id' => $client->id]) }}" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('projectsquare::users.add_user') }}</a>
@endsection