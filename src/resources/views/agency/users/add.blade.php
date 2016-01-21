@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li><a href="{{ route('users_index') }}">{{ trans('gateway::users.users_list') }}</a></li>
        <li class="active">{{ trans('gateway::users.add_user') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::users.add_user') }}</h1>
    </div>

    @include('gateway::agency.users.form', [
        'form_action' => route('users_store'),
    ])
@endsection