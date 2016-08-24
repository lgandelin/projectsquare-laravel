@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('projects_index') }}">{{ trans('projectsquare::projects.projects_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::projects.edit_project') }}</li>
    </ol>-->
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::projects.edit_project') }}</h1>
            </div>

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

            @include('projectsquare::agency.projects.form', [
                'form_action' => route('projects_update'),
                'project_id' => $project->id,
                'project_name' => $project->name,
                'project_website_front_url' => $project->website_front_url,
                'project_website_back_url' => $project->website_back_url,
                'project_color' => $project->color,
                'project_tasks_scheduled_time' => $project->tasks_scheduled_time,
                'project_tickets_scheduled_time' => $project->tickets_scheduled_time,
            ])
        </div>
    </div>
@endsection