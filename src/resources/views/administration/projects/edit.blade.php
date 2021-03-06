@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="agency-projects-template {{ $tab }}-project-template">

            <ul class="tabs">
                <li @if ($tab == 'infos')class="current"@endif><a href="{{ route('projects_edit', ['uuid' => $project->id]) }}">{{ __('projectsquare::project_infos.tabs.infos') }}</a></li>
                <li @if ($tab == 'team')class="current"@endif><a href="{{ route('projects_edit_team', ['uuid' => $project->id]) }}">{{ __('projectsquare::project_infos.tabs.team') }}</a></li>
                <li @if ($tab == 'tasks')class="current"@endif><a href="{{ route('projects_edit_tasks', ['uuid' => $project->id]) }}">{{ __('projectsquare::project_infos.tabs.tasks') }}</a></li>
                <li @if ($tab == 'attribution')class="current"@endif><a href="{{ route('projects_edit_attribution', ['uuid' => $project->id]) }}">{{ __('projectsquare::project_infos.tabs.attribution') }}</a></li>
                <li @if ($tab == 'config')class="current"@endif><a href="{{ route('projects_edit_config', ['uuid' => $project->id]) }}">{{ __('projectsquare::project_infos.tabs.configuration') }}</a></li>
                <li class="border-bottom"></li>
            </ul>

            <div class="templates">

                @include('projectsquare::administration.projects.' . $tab, [
                    'form_action' => route('projects_update'),
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_website_front_url' => $project->website_front_url,
                    'project_website_back_url' => $project->website_back_url,
                    'project_color' => $project->color,
                    'project_status_id' => $project->status_id,
                    'error' => isset($error) ? $error : null,
                    'confirmation' => isset($confirmation) ? $confirmation : null,
                ])
            </div>
        </div>
    </div>
@endsection