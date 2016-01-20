@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Projets</li>
    </ol>

    <div class="page-header">
        <h1>Projets</h1>
    </div>
@endsection