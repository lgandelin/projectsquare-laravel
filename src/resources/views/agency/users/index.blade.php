@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::users.users_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::users.users_list') }}</h1>
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
            <th>{{ trans('projectsquare::users.name') }}</th>
            <th>{{ trans('projectsquare::users.email') }}</th>
            <th>{{ trans('projectsquare::users.client') }}</th>
            <th>{{ trans('projectsquare::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->complete_name }}</td>
                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                    <td>@if (isset($user->client)){{ $user->client->name }}@endif</td>
                    <td>
                        <a href="{{ route('users_edit', ['id' => $user->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                        <a href="{{ route('users_delete', ['id' => $user->id]) }}" class="btn btn-danger btn-delete"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $users->render() !!}
    </div>

    <a href="{{ route('users_add') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('projectsquare::users.add_user') }}</a>
@endsection