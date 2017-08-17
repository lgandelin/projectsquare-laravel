@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tickets'])

    <div class="templates project-template project-tickets-template">
        @include('projectsquare::project.tickets.middle-column')
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/project.js') }}"></script>
@endsection