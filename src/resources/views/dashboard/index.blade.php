@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li class="active">Tableau de bord</li>
    </ol>

    <div class="page-header">
        <h1>Tableau de bord</h1>
    </div>

    <div class="row dashboard-content">
        <div class="col-md-3">
            <div class="block">
                <h3>Derniers tickets</h3>
                <div class="block-content"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="block">
                <h3>Alertes monitoring</h3>
                <div class="block-content"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="block">
                <h3>Calendrier</h3>
                <div class="block-content"></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="block">
                <h3>Derniers messages</h3>
                <div class="block-content"></div>
            </div>
        </div>
    </div>
@endsection