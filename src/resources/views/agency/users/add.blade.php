@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('users_index') }}">{{ trans('projectsquare::users.users_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::users.add_user') }}</li>
    </ol>
    <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::users.add_user') }}</h1>
        </div>

        @include('projectsquare::agency.users.form', [
            'form_action' => route('users_store'),
            'password_field' => true,
        ])
    </div>
@endsection