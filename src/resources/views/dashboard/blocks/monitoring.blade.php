<div class="block">
    <h3>{{ trans('projectsquare::dashboard.monitoring_alerts') }}</h3>
    <div class="block-content table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
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
                <tr>
                    <td>{{ $alert->id }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($alert->created_at)) }}</td>
                    <td><span class="badge">{{ $alert->type }}</span></td>
                    <td><a href="{{ route('project_index', ['id' => $alert->project->id]) }}"><span class="label" style="background: {{ $alert->project->color }}">{{ $alert->project->client->name }}</span> {{ $alert->project->name }}</a></td>
                    <td>
                        @if ($alert->type == 'WEBSITE_LOADING_TIME')
                            {{ number_format($alert->variables->loading_time, 2) }}s
                        @elseif ($alert->type == 'WEBSITE_STATUS_CODE')
                            {{ $alert->variables->status_code }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('project_monitoring', ['id' => $alert->project->id]) }}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('projectsquare::dashboard.see_project') }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>