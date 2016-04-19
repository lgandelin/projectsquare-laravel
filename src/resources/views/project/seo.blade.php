@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'seo'])

    <div class="seo-template">
        <h1 class="page-header">{{ trans('projectsquare::project.seo') }}</h1>

        <div id="embed-api-auth-container" style="display: none;"></div>

        <div class="row">
            <div class="col-lg-12">
                <h3>Indicateurs</h3>
                <br>
            </div>

            <div class="col-lg-4">
                <p>Sessions : <span id="sessions-count" class="number"></span></p>
                <p>Utilisateurs : <span id="users-count" class="number"></span></p>
                <p>Pages par visite : <span id="pages-per-visit" class="number"></span></p>
            </div>

            <div class="col-lg-4">
                <p>Pages vues : <span id="page-views-count" class="number"></span></p>
                <p>Durée moyenne session : <span id="avg-session-duration" class="number"></span></p>
            </div>

            <div class="col-lg-4">
                <p>Taux de rebond : <span id="bounce-rate" class="number"></span></p>
                <p>Nouvelles visites : <span id="new-visits-pct" class="number"></span></p>
            </div>
        </div>

        <div class="row" style="margin-top: 5rem">

            <div class="col-lg-12">
                <h3>Sessions</h3>
                <div id="chart-container" class="loading" style="min-height: 250px"></div>
            </div>

            <div class="col-lg-4">
                <h3>Navigateurs</h3>
                <div id="chart-3-container" class="loading" style="min-height: 250px"></div>
            </div>

            <div class="col-lg-4">
                <h3>Plateforme</h3>
                <div id="chart-4-container" class="loading" style="min-height: 250px"></div>
            </div>

            <div class="col-lg-4">
                <h3>Pays</h3>
                <div id="chart-2-container" class="loading" style="min-height: 250px"></div>
            </div>
        </div>
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

            var dataChart = new gapi.analytics.googleCharts.DataChart({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                },
                chart: {
                    container: 'chart-container',
                    type: 'LINE',
                    options: {
                        width: '100%'
                    }
                }
            });
            dataChart.execute();

            var dataChart2 = new gapi.analytics.googleCharts.DataChart({
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
            });
            dataChart2.execute();

            var dataChart3 = new gapi.analytics.googleCharts.DataChart({
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
            });
            dataChart3.execute();


            var dataChart4 = new gapi.analytics.googleCharts.DataChart({
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
            });
            dataChart4.execute();


            var report = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:sessions',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseInt(row[1]);
                    });
                    $('#sessions-count').text(total);
                }
            });

            report.execute();


            var report2 = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:users',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report2.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseInt(row[1]);
                    });
                    $('#users-count').text(total);
                }
            });

            report2.execute();




            var report3 = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:avgSessionDuration',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report3.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#avg-session-duration').text(formatToSeconds(total));
                }
            });

            report3.execute();



            var report4 = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:bounceRate',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report4.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#bounce-rate').text(formatFloat(total) + '%');
                }
            });

            report4.execute();





            var report5 = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:pageviews',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report5.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseInt(row[1]);
                    });
                    $('#page-views-count').text(total);
                }
            });

            report5.execute();




            var report6 = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:percentNewSessions',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report6.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#new-visits-pct').text(formatFloat(total) + '%');
                }
            });

            report6.execute();




            var report7 = new gapi.analytics.report.Data({
                query: {
                    ids: viewID,
                    metrics: 'ga:pageviewsPerSession',
                    dimensions: 'ga:date',
                    'start-date': start_date,
                    'end-date': end_date,
                }
            });

            report7.on('success', function handleCoreAPIResponse(resultsAsObject) {
                var total = 0;

                if (resultsAsObject.rows.length > 0) {
                    resultsAsObject.rows.forEach(function calculateTotal(row) {
                        total += parseFloat(row[1]);
                    });
                    total /= resultsAsObject.rows.length;
                    $('#pages-per-visit').text(formatFloat(total));
                }
            });

            report7.execute();
        });

        function formatFloat(value) {
            return Math.round(value * 100) / 100;
        }

        function formatToSeconds(value) {
            return moment().startOf('day').seconds(value).format('H:mm:ss');
        }
    </script>
@endsection