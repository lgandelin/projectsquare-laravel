@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'progress'])
    <div class="content-page">
        <div class="templates progress-template">
            <h1 class="page-header">{{ trans('projectsquare::project.progress') }}
                {{--@include('projectsquare::includes.tooltip', [
                    'text' => trans('projectsquare::tooltips.progress_title')
                ])--}}
            </h1>

            <div class="row">
                @include('projectsquare::management.progress.includes.project_progress', ['project' => $project, 'disable_header' => true])
            </div>

        </div>
    </div>
@endsection