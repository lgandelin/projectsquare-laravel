@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
        <li><a href="{{ route('clients_index') }}">{{ trans('gateway::clients.clients_list') }}</a></li>
        <li class="active">{{ trans('gateway::clients.add_client') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::clients.add_client') }}</h1>
    </div>

    @include('gateway::agency.clients.form', [
        'form_action' => route('clients_store'),
    ])
@endsection