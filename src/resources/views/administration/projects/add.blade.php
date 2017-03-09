@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="agency-projects-template">

            <ul class="tabs">
                <li class="current"><a href="{{ route('projects_store') }}">Infos</a></li>
                <li><a>Equipe</a></li>
                <li><a>Tâches</a></li>
                <li><a>Attribution</a></li>
                <li><a>Configuration</a></li>
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