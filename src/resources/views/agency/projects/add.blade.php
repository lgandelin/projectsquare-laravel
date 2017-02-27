@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="agency-projects-template">

            <ul class="tabs">
                <li class="current"><a href="{{ route('projects_store') }}">Infos</a></li>
                <li><a href="#">Tâches</a></li>
                <li><a href="#">Attribution</a></li>
                <li><a href="#">Configuration</a></li>
                <li class="border-bottom"></li>
            </ul>

            <div class="templates">

                <div class="page-header">
                    <h1>{{ trans('projectsquare::projects.add_project') }}</h1>
                </div>

                @include('projectsquare::agency.projects.infos', [
                    'form_action' => route('projects_store'),
                ])
            </div>
        </div>
    </div>
@endsection