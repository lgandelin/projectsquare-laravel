@extends('projectsquare::default')

@section('content')

    <div class="error-template">
        <div class="page-header">
            <h1>Page non trouvée</h1>
        </div>

        <p>Votre page n'a pas pu être trouvée. Veuillez vous assurer que vous avez tapé la bonne URL.</p>

        <a href="{{ route('dashboard') }}" class="btn button">Retour au tableau de bord</a>
    </div>

@endsection
