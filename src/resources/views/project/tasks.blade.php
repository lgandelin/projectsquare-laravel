@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tasks'])

    <div class="templates project-template project-tasks-template">
        @include('projectsquare::project.tasks.middle-column')
    </div>

@endsection