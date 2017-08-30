@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tasks'])

    <div class="templates project-template project-tasks-template">
        @include('projectsquare::project.tasks.middle-column')
    </div>

    <a style="margin-top: 2rem;" href="{{ route('tasks_add') }}" class="btn pull-right add create-task"></a>

@endsection