@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li><a href="{{ route('agency_index') }}">Agence</a></li>
        <li class="active">Gestion des profils</li>
    </ol>

    <div class="page-header">
        <h1>Gestion des profils</h1>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th width="50">#</th>
            <th>Profil</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a href="#" class="btn btn-primary">Editer</a>
                        <a href="#" class="btn btn-danger">Supprimer</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="#" class="btn btn-success">Ajouter un profil</a>
@endsection