@extends('projectsquare::app')

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('projectsquare::includes.top_bar')
            </div>
        </div>

        <div class="row">
            @if (!$is_client)
                <div class="col-lg-2 col-md-4 col-sm-4">
                    @include('projectsquare::includes.left_bar', [
                        'current_project_id' => ($current_project ? $current_project->id : null),
                        'is_client' => $is_client,
                    ])
                </div>

                <div class="col-lg-10 col-md-8 col-sm-8">
            @else
                <div class="col-lg-12 col-md-12 col-sm-12">
            @endif
                @yield('content')
            </div>
        </div>
    </div>
@endsection