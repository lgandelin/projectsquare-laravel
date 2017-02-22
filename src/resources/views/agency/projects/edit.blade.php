@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="agency-projects-template">

            <ul class="tabs">
                <li @if ($tab == 'infos')class="current"@endif><a href="{{ route('projects_edit', ['uuid' => $project->id]) }}">Infos</a></li>
                <li @if ($tab == 'tasks')class="current"@endif><a href="{{ route('projects_edit_tasks', ['uuid' => $project->id]) }}">Tâches</a></li>
                <li><a href="{{ route('projects_edit_team', ['uuid' => $project->id]) }}">Equipe</a></li>
                <li @if ($tab == 'config')class="current"@endif><a href="{{ route('projects_edit_config', ['uuid' => $project->id]) }}">Configuration</a></li>
                <li class="border-bottom"></li>
            </ul>

            <div class="templates">

                @if (isset($error))
                    <div class="info bg-danger">
                        {{ $error }}
                    </div>
                @endif

                @if (isset($confirmation))
                    <div class="info bg-success">
                        {{ $confirmation }}
                    </div>
                @endif

                @include('projectsquare::agency.projects.' . $tab, [
                    'form_action' => route('projects_update'),
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_website_front_url' => $project->website_front_url,
                    'project_website_back_url' => $project->website_back_url,
                    'project_color' => $project->color,
                    'project_tasks_scheduled_time' => $project->tasks_scheduled_time,
                    'project_tickets_scheduled_time' => $project->tickets_scheduled_time,
                    'error' => isset($error) ? $error : null,
                    'confirmation' => isset($confirmation) ? $confirmation : null,
                ])
            </div>
        </div>
    </div>
@endsection