@extends('gateway::app')

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('gateway::includes.top_bar')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-4">
                @include('gateway::includes.left_bar', ['current_project_id' => ($current_project ? $current_project->id : null)])
            </div>

            <div class="col-lg-9 col-md-8 col-sm-8">
                @yield('content')
            </div>
        </div>
    </div>
@endsection