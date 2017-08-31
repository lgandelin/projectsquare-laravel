@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tickets'])

    <a style="margin-top: 2rem;" href="{{ route('tickets_add') }}" class="btn pull-right add create-ticket"></a>

    <div class="templates project-template project-tickets-template">
        @include('projectsquare::project.tickets.middle-column')
    </div>

@endsection