@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates progress-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::progress.project_progress') }}</h1>
            </div>


            @foreach ($projects as $i => $project)
                @if ($i%2 == 0)<div class="row">@endif
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="project">
                        <div class="header" style="background:{{ $project->color }}">{{ $project->name }}</div>
                        <table class="table table-bordered">
                            <tr>
                                <td>{{ trans('projectsquare::progress.phases_tasks') }}</td>
                                <td>{{ trans('projectsquare::progress.progress') }}</td>
                            </tr>

                            @foreach ($project->phases as $phase)
                                <tr>
                                    <td class="phase">
                                        <span class="name">{{ $phase->name }}</span>

                                        <div class="tasks">
                                            @foreach ($phase->tasks as $task)
                                                <div class="task" style="@if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)background-color: #5497aa; @endif"></div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="progress-percentage" width="15%">
                                        33 %
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="progress-percentage total-percentage">38 %</td>
                                </tr>
                            </tr>
                        </table>
                    </div>
                </div>
                @if ($i%2 == 1)</div>@endif
            @endforeach
        </div>
    </div>
@endsection
