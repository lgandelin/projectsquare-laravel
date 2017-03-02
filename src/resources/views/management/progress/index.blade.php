@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates progress-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::progress.project_progress') }}</h1>
            </div>

            <div class="row">
                @foreach ($projects as $i => $project)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <div class="project">
                            <div class="header" style="background:{{ $project->color }}">{{ $project->name }}</div>
                            <table class="table table-bordered">
                                <!--<tr>
                                    <td>{{ trans('projectsquare::progress.phases_tasks') }}</td>
                                    <td>{{ trans('projectsquare::progress.progress') }}</td>
                                </tr>-->

                                @foreach ($project->phases as $phase)
                                    <tr>
                                        <td class="phase">
                                            <span class="name">{{ $phase->name }} @if ($phase->estimatedDuration > 0)<span class="duration">{{ $phase->estimatedDuration }} jour(s)</span>@endif</span>

                                            <div class="tasks">
                                                @foreach ($phase->tasks as $task)
                                                    <div class="task" style="@if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)background-color: #5497aa; @endif">
                                                        <div class="description">
                                                            <span class="name">{{ $task->title }}</span> @if ($task->estimatedTimeDays > 0) : <span class="duration"> {{ $task->estimatedTimeDays }} jour(s)</span> @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="progress-percentage" width="20%">
                                            @if ($phase->progress !== null && $phase->progress !== ""){{ $phase->progress }} %@endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>&nbsp;</td>
                                    <td width="20%" class="progress-percentage total-percentage">@if ($project->progress !== null && $project->progress !== ""){{ $project->progress }} %@endif</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
