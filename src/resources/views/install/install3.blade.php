@extends('projectsquare::app')

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 top-bar">
                <div class="pull-left">
                    <h1 class="logo">Installation</h1>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 5rem;">
            <div class="col-lg-6 col-lg-offset-3">
                <h3>Installation terminée !</h3>
                <p>Vous allez être redirigés vers la page d'accueil dans 10 secondes.<br/><br/>
                    Si vous ne souhaitez pas attendre <a href="{{ route('dashboard') }}"><span class="btn btn-primary">cliquez ici</span></a>
                </p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                window.location.href = "{{ route('dashboard') }}";
            }, 10000);
        });
    </script>
@endsection