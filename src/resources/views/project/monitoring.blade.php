@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'monitoring'])
    <div class="content-page">
        <div class="templates monitoring-template">
            <h1 class="page-header">{{ __('projectsquare::project.monitoring') }}
                @include('projectsquare::includes.tooltip', [
                    'text' => __('projectsquare::tooltips.monitoring_title')
                ])
            </h1>

            <div class="row" style="margin-top: 5rem">
                <div id="loading_time" class="col-md-8"></div>
                <div id="key_numbers" class="col-md-2 col-md-offset-1">
                    <span>{{ __('projectsquare::monitoring.synthesis') }}</span>

                    <div class="content">
                        <ul>
                            <li>{{ __('projectsquare::monitoring.availability_percentage') }} :
                                @include('projectsquare::includes.tooltip', [
                                    'text' => __('projectsquare::tooltips.monitoring.disponibility')
                                ])
                                <span class="number" style="color: #57c17b">{{ number_format($availability_percentage, 2) }}%</span>
                            </li>
                            <li>{{ __('projectsquare::monitoring.average_loading_time') }} :
                                 @include('projectsquare::includes.tooltip', [
                                    'text' => __('projectsquare::tooltips.monitoring.average_loading_time')
                                ])
                                <span class="number" style="color: #57c17b">{{ number_format($average_loading_time, 2) }}s</span>
                            </li>
                            <li>{{ __('projectsquare::monitoring.longest_loading_time') }} :
                                 <!--@include('projectsquare::includes.tooltip', [
                                    'text' => __('projectsquare::tooltips.monitoring.max_loading_time')
                                ])-->
                                <span class="number" style="color: #fc9a24">{{ number_format($max_loading_time, 2) }}s</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 5rem">
                <div id="status_codes_pie" class="col-md-4"></div>
                <div id="loading_times_pie" class="col-md-4"></div>
            </div>
        </div>
    </div>

    <script>
        var data = {!! json_encode($requests) !!};

        var status_code_20x = {{ $status_codes['20x'] }};
        var status_code_40x = {{ $status_codes['40x'] }};
        var status_code_50x = {{ $status_codes['50x'] }};

        var loading_times_below1 = {{ $loading_times['below1'] }};
        var loading_times_between1and1_5 = {{ $loading_times['between1and1.5'] }};
        var loading_times_between1_5and3 = {{ $loading_times['between1.5and3'] }};
        var loading_times_morethan3 = {{ $loading_times['morethan3'] }};

        $(function () {
            $('#loading_time').highcharts({
                chart: {
                    zoomType: 'x'
                },
                title: {
                    text: "{!! __('projectsquare::monitoring.website_loading_time') !!} {{ $project->websiteFrontURL }}"
                },
                subtitle: {
                    text: "{!! __('projectsquare::monitoring.last_24h') !!}"
                },
                xAxis: { type: 'datetime' },
                yAxis: {
                    title: {
                        text: "{!! __('projectsquare::monitoring.loading_time_in_seconds') !!}"
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
                    name: "{{ $project->name }}",
                    data: data
                }],
                credits: {
                    enabled: false
                }
            });
        });

        $(function () {
            $('#status_codes_pie').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: "{!! __('projectsquare::monitoring.statuses_codes') !!}"
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
                        y: status_code_40x
                    }, {
                        name: '50x - Erreur serveur',
                        y: status_code_50x
                    }]
                }],
                credits: {
                    enabled: false
                }
            });
        });

        $(function () {
            $('#loading_times_pie').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: "{!! __('projectsquare::monitoring.website_loading_time') !!}"
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
                        colors: ['#57c17b', '#76D295', '#fc9a24', '#DF5A49']
                    }
                },
                series: [{
                    name: "{!! __('projectsquare::monitoring.loading_time_in_seconds') !!}",
                    colorByPoint: true,
                    data: [{
                        name: 'Moins de 1s',
                        y: loading_times_below1
                    }, {
                        name: 'Entre 1 et 1.5s',
                        y: loading_times_between1and1_5
                    }, {
                        name: 'Entre 1.5 et 3s',
                        y: loading_times_between1_5and3
                    }, {
                        name: 'Plus de 3s',
                        y: loading_times_morethan3
                    }]
                }],
                credits: {
                    enabled: false
                }
            });
        });
    </script>
    <script src="{{ asset('js/vendor/highcharts.js') }}"></script>

    <script>
        Highcharts.setOptions({

            lang : {
                resetZoom : 'Rétablir zoom'

            }
        });
    </script>
@endsection