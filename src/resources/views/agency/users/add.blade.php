@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::users.add_user') }}</h1>
            </div>

            @include('projectsquare::agency.users.form', [
                'form_action' => route('users_store'),
                'password_field' => true,
            ])
        </div>
    </div>
@endsection