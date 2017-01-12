@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::clients.edit_user') }}</h1>
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
        </div>
    </div>
@endsection