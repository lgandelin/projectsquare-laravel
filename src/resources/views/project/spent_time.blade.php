@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'spent_time'])
    <div class="content-page">
        <div class="templates spent-time-template">
            <h1 class="page-header">{{ trans('projectsquare::project.spent_time') }}
                {{--@include('projectsquare::includes.tooltip', [
                    'text' => trans('projectsquare::tooltips.spent_time_title')
                ])--}}
            </h1>

            <div class="row">
                @include('projectsquare::management.spent_time.includes.project_spent_time', ['project' => $project, 'project_interface' => true])
            </div>
        </div>
    </div>
@endsection