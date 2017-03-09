@extends('projectsquare::default')

@section('content')

    <div class="error-template">
        <div class="page-header">
            <h1>Une erreur est survenue</h1>
        </div>

        <p>Une erreur est survenue lors de la dernière opération effectuée. Veuillez nous excuser pour la gêne occasionnée, l'équipe technique de Projectsquare va intervenir sur le problème.</p>

        <a href="{{ route('dashboard') }}" class="btn button">Retour au tableau de bord</a>
    </div>

@endsection
