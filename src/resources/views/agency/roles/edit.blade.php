@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li><a href="{{ route('agency_index') }}">Agence</a></li>
        <li class="active">Gestion des profils</li>
    </ol>

    <div class="page-header">
        <h1>Edition du profil : {{ $role->name }}</h1>
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

    <form action="{{ route('roles_update') }}" method="post">
        <div class="form-group">
            <label for="name">Libellé</label>
            <input class="form-control" type="text" placeholder="ex: développeur, ..." name="name" value="{{ $role->name }}" />
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Valider" />
            <a href="{{ route('roles_index') }}" class="btn btn-default">Retour</a>
        </div>

        <input type="hidden" name="role_id" value="{{ $role->id }}" />

        {!! csrf_field() !!}
    </form>
@endsection