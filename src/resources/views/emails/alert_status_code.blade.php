<strong>{{ trans('projectsquare::email.project') }}</strong> @if (isset($project->clientName)) {{ $project->clientName }} - @endif{{ $project->name }}<br/>
<strong>{{ trans('projectsquare::email.status_code') }}</strong> {{ $request->status_code }}<br/>
<strong>{{ trans('projectsquare::email.date') }}</strong> {{ $request->created_at }}