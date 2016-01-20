@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Messages</li>
    </ol>

    <h1>Messages</h1>
@endsection