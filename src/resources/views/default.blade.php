@extends('projectsquare::app')

@section('main-content')
    <div class="container-fluid">
        <div class="row top-bar-container">
            @include('projectsquare::includes.top_bar')
        </div>

        <div class="row">
            @if (isset($is_client) && !$is_client)
                @include('projectsquare::includes.left_bar', [
                    'current_project_id' => ($current_project ? $current_project->id : null),
                    'is_client' => $is_client,
                    'current_route' => $current_route
                ])
                <div class="content @if ($left_bar == 'closed') content-expanded @endif">
            @else
                <div>
            @endif
                @yield('content')
            </div>
        </div>
    </div>
@endsection