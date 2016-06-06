@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">{{ trans('projectsquare::my.panel_title') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::my.panel_title') }}</h1>
    </div>
@endsection