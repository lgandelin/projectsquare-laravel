@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates progress-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::progress.project_progress') }}</h1>
            </div>

            <div class="row">
                @foreach ($projects as $i => $project)
                    @include('projectsquare::management.progress.includes.project_progress', ['project' => $project])
                @endforeach
            </div>
        </div>
    </div>
@endsection
