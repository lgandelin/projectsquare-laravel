@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar', ['active' => 'monitoring'])

    <div class="project-template">
        <h1 class="page-header">{{ trans('gateway::project.summary') }}</h1>

        <h3>{{ trans('gateway::project.monitoring') }}</h3>

        <p>Nombre de requêtes : {{ count($requests) }}</p>

        <p>Temps de chargement moyen : {{ number_format($average_loading_time, 2) }} s</p>

        <div id="loading_time" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>

    <script>
        var data = {!! json_encode($requests) !!};

        $(function () {
            $('#loading_time').highcharts({
                chart: {
                    zoomType: 'x'
                },
                title: {
                    text: 'Performances du site http://web-access.fr'
                },
                subtitle: {
                    text: 'Temps de chargement de la page d\'accueil'
                },
                xAxis: { type: 'datetime' },
                yAxis: {
                    title: {
                        text: 'Temps de chargement (en s)'
                    },
                    min: 0
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br>',
                    pointFormat: '{point.x:%H:%M} {point.y:.2f} s'
                },

                plotOptions: {
                    spline: {
                        marker: {
                            enabled: true
                        }
                    }
                },

                series: [{
                    name: 'Webaccess',
                    data: data
                }]
            });
        });
    </script>
    <script src="{{ asset('js/vendor/highcharts.js') }}"></script>
@endsection