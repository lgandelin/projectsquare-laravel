@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates progress-template">
            <div class="page-header">
                <h1>{{ __('projectsquare::progress.project_progress') }}</h1>
            </div>

            <div class="flex-grid">
                @foreach ($projects as $i => $project)
                    @include('projectsquare::management.progress.includes.project_progress', ['project' => $project])
                @endforeach
            </div>
        </div>
    </div>
@endsection
