@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('clients_index') }}">{{ trans('projectsquare::clients.clients_list') }}</a></li>
        <li><a href="{{ route('clients_edit', ['id' => $client->id]) }}">{{ trans('projectsquare::clients.edit_client') }}</a></li>
        <li class="active">{{ trans('projectsquare::clients.edit_user') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::users.edit_user') }}</h1>
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

    @include('projectsquare::agency.clients.users.form', [
        'form_action' => route('clients_update_user'),
        'user_id' => $user->id,
        'user_first_name' => $user->firstName,
        'user_last_name' => $user->lastName,
        'user_email' => $user->email,
        'user_mobile' => $user->mobile,
        'user_phone' => $user->phone,
        'user_role' => $user->clientRole,
        'is_administrator' => $user->isAdministrator,
        'password_field' => false,
        'client_id' => $client->id
    ])
@endsection