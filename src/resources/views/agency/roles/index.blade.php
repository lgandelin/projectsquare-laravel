@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li class="active">{{ trans('gateway::roles.roles_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::roles.roles_list') }}</h1>
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
                <th>{{ trans('gateway::roles.role') }}</th>
                <th>{{ trans('gateway::generic.action') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a href="{{ route('roles_edit', ['id' => $role->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('gateway::generic.edit') }}</a>
                        <a href="{{ route('roles_delete', ['id' => $role->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> {{ trans('gateway::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $roles->render() !!}
    </div>

    <a href="{{ route('roles_add') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('gateway::roles.add_role') }}</a>
@endsection