@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('roles_index') }}">{{ trans('projectsquare::roles.roles_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::roles.edit_role') }}</li>
    </ol>-->
    <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::roles.edit_role') }}</h1>
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

        @include('projectsquare::agency.roles.form', [
            'form_action' => route('roles_update'),
            'role_id' => $role->id,
            'role_name' => $role->name
        ])
    </div>
@endsection