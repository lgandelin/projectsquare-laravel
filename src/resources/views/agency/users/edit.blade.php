@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li><a href="{{ route('users_index') }}">{{ trans('gateway::users.users_list') }}</a></li>
        <li class="active">{{ trans('gateway::users.edit_user') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::users.edit_user') }}</h1>
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

    @include('gateway::agency.users.form', [
        'form_action' => route('users_update'),
        'user_id' => $user->id,
        'user_first_name' => $user->first_name,
        'user_last_name' => $user->last_name,
        'user_email' => $user->email,
    ])
@endsection