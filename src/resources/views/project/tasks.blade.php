@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tasks'])

    <div class="templates project-template project-tasks-template">
        @include('projectsquare::project.tasks.middle-column')
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/project-tasks.js') }}"></script>
@endsection