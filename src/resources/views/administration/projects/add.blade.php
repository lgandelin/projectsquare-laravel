@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="agency-projects-template">

            <ul class="tabs">
                <li class="current"><a href="{{ route('projects_store') }}">Infos</a></li>
                <li><a href="#">Equipe</a></li>
                <li><a href="#">Tâches</a></li>
                <li><a href="#">Attribution</a></li>
                <li><a href="#">Configuration</a></li>
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