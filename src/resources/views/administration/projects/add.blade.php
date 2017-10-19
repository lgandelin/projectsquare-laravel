@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="agency-projects-template">

            <ul class="tabs">
                <li class="current"><a href="{{ route('projects_store') }}">{{ trans('projectsquare::project_infos.tabs.infos') }}</a></li>
                <li><a>{{ trans('projectsquare::project_infos.tabs.team') }}</a></li>
                <li><a>{{ trans('projectsquare::project_infos.tabs.tasks') }}</a></li>
                <li><a>{{ trans('projectsquare::project_infos.tabs.attribution') }}</a></li>
                <li><a>{{ trans('projectsquare::project_infos.tabs.configuration') }}</a></li>
                <li class="border-bottom"></li>
            </ul>

            <div class="templates">
                @include('projectsquare::administration.projects.infos', [
                    'form_action' => route('projects_store'),
                ])
            </div>
        </div>
    </div>
@endsection