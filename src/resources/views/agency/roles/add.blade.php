@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li><a href="{{ route('agency_index') }}">Agence</a></li>
        <li class="active">Gestion des profils</li>
    </ol>

    <div class="page-header">
        <h1>Ajouter un profil</h1>
    </div>

    <form action="{{ route('roles_store') }}" method="post">
        <div class="form-group">
            <label for="name">Libellé</label>
            <input class="form-control" type="text" placeholder="ex: développeur, ..." name="name" />
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Valider" />
            <a href="{{ route('roles_index') }}" class="btn btn-default">Retour</a>
        </div>

        {!! csrf_field() !!}
    </form>
@endsection