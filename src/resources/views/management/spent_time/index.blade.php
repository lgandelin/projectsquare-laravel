@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates spent-time-template">
            <div class="page-header">
                <h1>{{ __('projectsquare::spent_time.spent_time') }}</h1>
            </div>

            <div class="flex-grid">
                @foreach ($projects as $i => $project)
                    @include('projectsquare::management.spent_time.includes.project_spent_time', ['project' => $project])
                @endforeach
            </div>
        </div>
    </div>
@endsection
