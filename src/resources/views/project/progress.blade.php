@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'progress'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.progress') }}</h1>

            @if (sizeof($tasks) > 0)
                <div class="row summary">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <h3>{{ trans('projectsquare::progress.task_statuses') }}</h3>
                        <ul>
                            <li><strong style="color: #5cb85c">{{ trans('projectsquare::progress.finished') }} : </strong> {{ $completed_tasks_count }} {{ trans('projectsquare::progress.tasks') }}
                            <li><strong style="color: #f0ad4e">{{ trans('projectsquare::progress.in_progress') }} : </strong> {{ $in_progress_tasks_count }} {{ trans('projectsquare::progress.tasks') }}
                            <li><strong style="color: #d9534f">{{ trans('projectsquare::progress.todo') }} :</strong> {{ $todo_tasks_count }} {{ trans('projectsquare::progress.tasks') }}
                            <li><strong>{{ trans('projectsquare::progress.total') }} : </strong> {{ sizeof($tasks) }} {{ trans('projectsquare::progress.tasks') }}
                        </ul>
                    </div>

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <h3>{{ trans('projectsquare::progress.progress_bar') }}</h3>
                        <div class="progress">
                            @if ($completed_tasks_count > 0)
                                <div class="progress-bar progress-bar-success" style="width: {{ floor($completed_tasks_count * 100 / sizeof($tasks)) }}%">
                                    {{ $completed_tasks_count }} {{ trans('projectsquare::progress.tasks') }}
                                </div>
                            @endif

                            @if ($in_progress_tasks_count > 0)
                                <div class="progress-bar progress-bar-warning" style="width: {{ floor($in_progress_tasks_count * 100 / sizeof($tasks)) }}%">
                                    {{ $in_progress_tasks_count }} {{ trans('projectsquare::progress.tasks') }}
                                </div>
                            @endif

                            @if ($in_progress_tasks_count > 0)
                                <div class="progress-bar progress-bar-danger" style="width: {{ floor($todo_tasks_count * 100 / sizeof($tasks)) }}%">
                                    {{ $todo_tasks_count }} {{ trans('projectsquare::progress.tasks') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-12">
                        <h3>{{ trans('projectsquare::progress.indicators') }}</h3>

                        {{ trans('projectsquare::progress.progress_percentage') }} : <span style="font-size: 25px">{{ floor($completed_tasks_count * 100 / sizeof($tasks)) }}%</span>
                    </div>
                </div>
            @else
                {{ trans('projectsquare::progress.no_task_yet') }}
            @endif
        </div>
    </div>

@endsection