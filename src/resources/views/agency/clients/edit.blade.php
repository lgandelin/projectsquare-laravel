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
        'client_name' => $client->name
    ])
@endsection