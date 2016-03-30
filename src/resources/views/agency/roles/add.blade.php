@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('roles_index') }}">{{ trans('projectsquare::roles.roles_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::roles.add_role') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::roles.add_role') }}</h1>
    </div>

    @include('projectsquare::agency.roles.form', [
        'form_action' => route('roles_store'),
    ])
@endsection