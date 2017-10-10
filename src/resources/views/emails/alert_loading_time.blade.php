<strong>{{ trans('projectsquare::email.project') }}</strong> @if (isset($project->clientName)) {{ $project->clientName }} - @endif{{ $project->name }}<br/>
<strong>{{ trans('projectsquare::email.loading_time') }}</strong> {{ $request->loading_time }}s<br/>
<strong>{{ trans('projectsquare::email.date') }}</strong> {{ $request->created_at }}