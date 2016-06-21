@extends('projectsquare::app')

@section('main-content')
    <div class="container-fluid">
        <div class="row top-bar-container">
            <div class="col-md-12">
                @include('projectsquare::includes.top_bar')
            </div>
        </div>

        <div class="row">
            @if (!$is_client)
               
                @include('projectsquare::includes.left_bar', [
                    'current_project_id' => ($current_project ? $current_project->id : null),
                    'is_client' => $is_client,
                    'current_route' => $current_route
                ])

                <div class="col-lg-10 col-md-8 col-sm-8 col-lg-offset-2 content">
    
            @else
                <div class="col-lg-12 col-md-12 col-sm-12">
            @endif
                @yield('content')
            </div>
        </div>
    </div>
@endsection