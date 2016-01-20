@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li><a href="{{ route('agency_index') }}">Agence</a></li>
        <li class="active">Gestion des projets</li>
    </ol>

    <div class="page-header">
        <h1>Gestion des projets</h1>
    </div>

@endsection