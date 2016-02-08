@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar', ['active' => 'monitoring'])

    <div class="project-template">
        <h1 class="page-header">{{ trans('gateway::project.summary') }}</h1>

        <h3>{{ trans('gateway::project.monitoring') }}</h3>

        <p>Nombre de requêtes : {{ count($requests) }}</p>

        <p>Temps de chargement moyen : {{ number_format($average_loading_time, 2) }} s</p>

        <div id="loading_time" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <div id="status_codes" style="width: 50%; height: 350px; margin-top: 10rem"></div>
    </div>

    <script>
        var data = {!! json_encode($requests) !!};
        var status_code_20x = {{ $status_codes['20x'] }};
        var status_code_40x = {{ $status_codes['40x'] }};
        var status_code_50x = {{ $status_codes['50x'] }};

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
                    pointFormat: '{point.x:%d/%m/%y %H:%M} => {point.y:.2f} s'
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

        $(function () {
            $('#status_codes').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Code statuts de réponse du serveur'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        colors: ['#57c17b', '#fc9a24', '#DF5A49']
                    }
                },
                series: [{
                    name: 'Code statuts',
                    colorByPoint: true,
                    data: [{
                        name: '20x - Succès',
                        y: status_code_20x
                    }, {
                        name: '40x - Erreur client',
                        y: status_code_40x,
                    }, {
                        name: '50x - Erreur serveur',
                        y: status_code_50x,
                    }]
                }]
            });
        });
    </script>
    <script src="{{ asset('js/vendor/highcharts.js') }}"></script>
@endsection