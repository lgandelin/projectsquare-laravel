@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'seo'])
    <div class="content-page">
        <div class="templates seo-template">
            <h1 class="page-header">{{ __('projectsquare::project.seo') }}
                 @include('projectsquare::includes.tooltip', [
                        'text' => __('projectsquare::tooltips.seo_title')
                  ])
            </h1>

            @if ($gaViewID)
                <div id="embed-api-auth-container" style="margin-bottom: 15px"></div>

                <div class="row">
                    <div class="col-lg-9">
                        <h3>{{ __('projectsquare::seo.indicator') }}</h3>
                        <br>

                        <div class="col-lg-4">
                            <p class="text-seo">{{ __('projectsquare::seo.sessions') }} <span id="sessions-count" class="number"></span></p>
                            <p class="text-seo">{{ __('projectsquare::seo.users') }} <span id="users-count" class="number"></span></p>
                            <p class="text-seo">{{ __('projectsquare::seo.pages_per_visit') }} <span id="pages-per-visit" class="number"></span></p>
                        </div>

                        <div class="col-lg-4">
                            <p class="text-seo">{{ __('projectsquare::seo.pages_views') }} <span id="page-views-count" class="number"></span></p>
                            <p class="text-seo">{{ __('projectsquare::seo.during_session') }} <span id="avg-session-duration" class="number"></span></p>
                        </div>

                        <div class="col-lg-4">
                            <p class="text-seo">{{ __('projectsquare::seo.rebound_rate') }} <span id="bounce-rate" class="number"></span></p>
                            <p class="text-seo">{{ __('projectsquare::seo.news_visits') }} <span id="new-visits-pct" class="number"></span></p>
                        </div>
                    </div>

                <div class="col-lg-3">
                    <h3>{{ __('projectsquare::seo.period') }}</h3>
                    <br>
                    <div class="form-group col-lg-6">
                        <label for="title" class="text-seo">{{ __('projectsquare::seo.start_date') }}</label>
                        <input class="form-control datepicker button" type="text" placeholder="" id="start_date" autocomplete="off" value="{{ $startDate }}" style="width:175px" />
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="title" class="text-seo">{{ __('projectsquare::seo.end_date') }}</label>
                        <input class="form-control datepicker button" type="text" placeholder="" id="end_date" autocomplete="off" value="{{ $endDate }}" style="width:175px" />
                    </div>
                    <div id="date-range-selector-1-container"></div>
                </div>
            </div>

            <div class="row" style="margin-top: 5rem">

                <div class="col-lg-12">
                    <h3>{{ __('projectsquare::seo.sessions') }}</h3>
                    <div id="chart-container" class="loading" style="min-height: 250px"></div>
                </div>
            </div>

            <div class="row" style="margin-top: 5rem">

                <div class="col-lg-4">
                    <h3>{{ __('projectsquare::seo.browsers') }}</h3>
                    <div id="chart-3-container" class="loading" style="min-height: 250px"></div>
                </div>

                <div class="col-lg-4">
                    <h3>{{ __('projectsquare::seo.platform') }}</h3>
                    <div id="chart-4-container" class="loading" style="min-height: 250px"></div>
                </div>

                <div class="col-lg-4">
                    <h3>{{ __('projectsquare::seo.countries') }}</h3>
                    <div id="chart-2-container" class="loading" style="min-height: 250px"></div>
                </div>
            </div>
        @else
            {{ __('projectsquare::seo.id') }}
        @endif
    </div>

    <script>
        (function(w,d,s,g,js,fs){
            g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
            js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
            js.src='https://apis.google.com/js/platform.js';
            fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
        }(window,document,'script'));
    </script>

    <script src="{{ asset('js/vendor/fullcalendar/lib/moment.min.js') }}"></script>
    <script>
        var start_date = '30daysAgo';
        var end_date = 'yesterday';
        var viewID = 'ga:{{ $gaViewID }}';

        gapi.analytics.ready(function() {

            gapi.analytics.auth.authorize({
                container: 'embed-api-auth-container',
                clientid: '77799009845-j8h0uaen79phl58fcsgptv34oqu1jdp7.apps.googleusercontent.com'
            });

            var charts = [];

            charts.push(new gapi.analytics.googleCharts.DataChart({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date
                },
                chart: {
                    container: 'chart-container',
                    type: 'LINE',
                    options: {
                        width: '100%'
                    }
                }
            }));

            charts.push(new gapi.analytics.googleCharts.DataChart({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:country',
                    'start-date': start_date,
                    'end-date': end_date,
                    'max-results': 5,
                    sort: '-ga:sessions'
                },
                chart: {
                    container: 'chart-2-container',
                    type: 'PIE',
                    options: {
                        width: '100%',
                        pieHole: 4/9
                    }
                }
            }));

            charts.push(new gapi.analytics.googleCharts.DataChart({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:browser',
                    'start-date': start_date,
                    'end-date': end_date,
                    'max-results': 5,
                    sort: '-ga:sessions'
                },
                chart: {
                    container: 'chart-3-container',
                    type: 'PIE',
                    options: {
                        width: '100%',
                        pieHole: 4/9
                    }
                }
            }));

            charts.push(new gapi.analytics.googleCharts.DataChart({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:deviceCategory',
                    'start-date': start_date,
                    'end-date': end_date,
                    'max-results': 5,
                    sort: '-ga:sessions'
                },
                chart: {
                    container: 'chart-4-container',
                    type: 'PIE',
                    options: {
                        width: '100%',
                        pieHole: 4/9
                    }
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseInt(row[1]);
                    });
                    $('#sessions-count').text(total);
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:users',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseInt(row[1]);
                    });
                    $('#users-count').text(total);
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:avgSessionDuration',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#avg-session-duration').text(formatToSeconds(total));
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:bounceRate',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#bounce-rate').text(formatFloat(total) + '%');
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:pageviews',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseInt(row[1]);
                    });
                    $('#page-views-count').text(total);
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:percentNewSessions',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#new-visits-pct').text(formatFloat(total) + '%');
                }
            }));

            charts.push(new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:pageviewsPerSession',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            }).on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#pages-per-visit').text(formatFloat(total));
                }
            }));

            for (i in charts) {
                charts[i].execute();
            }

            $('#start_date, #end_date').change(function() {
                var query = {
                    'start-date': moment($('#start_date').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                    'end-date': moment($('#end_date').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                };

                for (i in charts) {
                    charts[i].set({query: query}).execute();
                }
            });
        });

        function formatFloat(value) {
            return Math.round(value * 100) / 100;
        }

        function formatToSeconds(value) {
            return moment().startOf('day').seconds(value).format('H:mm:ss');
        }
    </script>
@endsection