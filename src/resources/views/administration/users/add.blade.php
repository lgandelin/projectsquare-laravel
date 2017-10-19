@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::users.add_user') }}</h1>
                 <a href="{{ route('users_index') }}" class="btn back"></a>
            </div>

            @include('projectsquare::administration.users.form', [
                'form_action' => route('users_store'),
                'password_field' => true,
            ])
        </div>
    </div>
@endsection