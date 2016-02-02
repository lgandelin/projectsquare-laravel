@extends('gateway::app')

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('gateway::includes.top_bar')
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                @include('gateway::includes.left_bar', ['current_project_id' => $current_project->id])
            </div>

            <div class="col-md-10">
                @yield('content')
            </div>
        </div>
    </div>
@endsection