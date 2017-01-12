@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::monitoring.alerts_monitoring') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.monitoring_title')
                    ])
                </h1>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>{{ trans('projectsquare::dashboard.alert_date') }}</th>
                        <th>{{ trans('projectsquare::dashboard.alert_type') }}</th>
                        <th>{{ trans('projectsquare::dashboard.alert_project') }}</th>
                        <th>{{ trans('projectsquare::dashboard.alert_variables') }}</th>
                        <th>{{ trans('projectsquare::generic.action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($alerts as $alert)
                        <?php $alert->variables = json_decode($alert->variables); ?>
                        <tr>
                            <td class="priorities" style="border-left: 5px solid {{ $alert->project->color }}"></td>
                            <td>{{ $alert->id }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($alert->created_at)) }}</td>
                            <td><span class="badge monitoring_badge">{{ $alert->type }}</span></td>
                            <td><a href="{{ route('project_index', ['id' => $alert->project->id]) }}"><span class="text">{{ $alert->project->client->name }}</span></a></td>
                            <td>
                                @if ($alert->type == 'WEBSITE_LOADING_TIME')
                                    {{ number_format($alert->variables->loading_time, 2) }}s
                                @elseif ($alert->type == 'WEBSITE_STATUS_CODE')
                                    {{ $alert->variables->status_code }}
                                @endif
                            </td>
                            <td align="right">
                                <a href="{{ route('project_monitoring', ['id' => $alert->project->id]) }}" class="btn see-more"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                {!! $alerts->render() !!}
            </div>
        </div>
    </div>
@endsection