@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::agency.panel_title') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::agency.panel_title') }}</h1>
    </div>
@endsection