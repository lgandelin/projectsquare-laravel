@extends('projectsquare::default')

@section('content')
     <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::users.edit_user') }}</h1>
                 <a href="{{ route('users_index') }}" class="btn back"></a>
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

            @include('projectsquare::administration.users.form', [
                'form_action' => route('users_update'),
                'user_id' => $user->id,
                'user_first_name' => $user->firstName,
                'user_last_name' => $user->lastName,
                'user_email' => $user->email,
                'user_role_id' => $user->roleID,
                'is_administrator' => $user->isAdministrator,
                'password_field' => false,
            ])
        </div>
    </div>
@endsection