@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li class="active">{{ trans('gateway::users.users_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::users.users_list') }}</h1>
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
            <th>{{ trans('gateway::users.name') }}</th>
            <th>{{ trans('gateway::users.email') }}</th>
            <th>{{ trans('gateway::users.client') }}</th>
            <th>{{ trans('gateway::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
            <td>@if (isset($user->client)){{ $user->client->name }}@endif</td>
            <td>
                <a href="{{ route('users_edit', ['id' => $user->id]) }}" class="btn btn-primary">{{ trans('gateway::generic.edit') }}</a>
                <a href="{{ route('users_delete', ['id' => $user->id]) }}" class="btn btn-danger">{{ trans('gateway::generic.delete') }}</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('users_add') }}" class="btn btn-success">{{ trans('gateway::users.add_user') }}</a>
@endsection