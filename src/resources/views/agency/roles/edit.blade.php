@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li><a href="{{ route('roles_index') }}">{{ trans('gateway::roles.roles_list') }}</a></li>
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

    @include('gateway::agency.roles.form', [
        'form_action' => route('roles_update'),
        'role_id' => $role->id,
        'role_name' => $role->name
    ])
@endsection