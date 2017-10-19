<strong>{{ __('projectsquare::email.project') }}</strong> @if (isset($project->clientName)) {{ $project->clientName }} - @endif{{ $project->name }}<br/>
<strong>{{ __('projectsquare::email.status_code') }}</strong> {{ $request->status_code }}<br/>
<strong>{{ __('projectsquare::email.date') }}</strong> {{ $request->created_at }}