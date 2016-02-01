@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">{{ $project->name }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ $project->name }}</h1>


    </div>
@endsection