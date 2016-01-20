@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li><a href="{{ route('agency_index') }}">Agence</a></li>
        <li><a href="{{ route('roles_index') }}">{{ trans('gateway::roles.list_role') }}</a></li>
        <li class="active">{{ trans('gateway::roles.edit_role') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::roles.edit_role') }}</h1>
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

    <form action="{{ route('roles_update') }}" method="post">
        <div class="form-group">
            <label for="name">{{ trans('gateway::roles.label') }}</label>
            <input class="form-control" type="text" placeholder="{{ trans('gateway::roles.name_placeholder') }}" name="name" value="{{ $role->name }}" />
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
            <a href="{{ route('roles_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
        </div>

        <input type="hidden" name="role_id" value="{{ $role->id }}" />

        {!! csrf_field() !!}
    </form>
@endsection