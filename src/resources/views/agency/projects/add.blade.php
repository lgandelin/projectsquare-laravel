@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('agency_index') }}">{{ trans('projectsquare::agency.panel_title') }}</a></li>
        <li><a href="{{ route('projects_index') }}">{{ trans('projectsquare::projects.projects_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::projects.add_project') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::projects.add_project') }}</h1>
    </div>

    @include('projectsquare::agency.projects.form', [
        'form_action' => route('projects_store'),
    ])
@endsection