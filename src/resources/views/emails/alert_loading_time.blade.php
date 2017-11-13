<strong>{{ __('projectsquare::email.project') }}</strong> @if (isset($project->clientName)) {{ $project->clientName }} - @endif{{ $project->name }}<br/>
<strong>{{ __('projectsquare::email.loading_time') }}</strong> {{ $request->loading_time }}s<br/>
<strong>{{ __('projectsquare::email.date') }}</strong> {{ $request->created_at }}