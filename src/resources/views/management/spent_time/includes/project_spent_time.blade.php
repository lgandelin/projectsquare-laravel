@if (sizeof($project->phases) > 0)
    <div class="col">
        <div class="project">
            @if (!isset($project_interface))<div class="header" style="background:{{ $project->color }}">{{ $project->name }}</div>@endif
            <table class="table table-bordered">
                <tr>
                    <td>{{ trans('projectsquare::spent_time.phases_tasks') }}</td>
                    <td>{{ trans('projectsquare::spent_time.spent_time') }}</td>
                </tr>

                @foreach ($project->phases as $phase)
                    <tr>
                        <td class="phase">
                            <span class="name">
                                {{ $phase->name }}
                            </span>

                            <div class="tasks">
                                @foreach ($phase->tasks as $task)
                                    <div class="task" style="@if ($task->spentTimeStatus == Webaccess\ProjectSquare\Entities\Task::SPENT_TIME_EXCEEDED) background-color: #f25f65;
                                    @elseif ($task->spentTimeStatus == Webaccess\ProjectSquare\Entities\Task::SPENT_TIME_NORMAL || $task->spentTimeStatus == Webaccess\ProjectSquare\Entities\Task::SPENT_TIME_AHEAD) background-color: #b0d878;@endif">
                                        <div class="description">

                                            @if (isset($task->allocatedUser))
                                                @include('projectsquare::includes.avatar', [
                                                    'id' => $task->allocatedUser->id,
                                                    'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                                ])
                                            @endif

                                            <span class="name">{{ $task->title }}</span>
                                            @if ($task->estimatedTimeDays > 0)<span class="duration"> <strong>Temps estimé :</strong> {{ $task->estimatedTimeDays }} jour(s)</span> @endif
                                            @if ($task->spentTimeDays > 0)<br/><span class="duration"> <strong>Temps passé :</strong> {{ $task->spentTimeDays }} jour(s)</span> @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="phase-value" width="20%" style="@if ($phase->differenceSpentEstimated > 0)color:#f25f65 @elseif ($phase->differenceSpentEstimated < 0) color:#b0d878 @endif ">
                            @if ($phase->differenceSpentEstimated > 0)+{{ $phase->differenceSpentEstimated }}j
                            @elseif ($phase->differenceSpentEstimated < 0){{ $phase->differenceSpentEstimated }}j
                            @else -
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="text-align: right;">Total</td>
                    <td width="20%" class="total" style="color: white; @if ($project->differenceSpentEstimated > 0) background-color: #f25f65;
                    @elseif ($project->differenceSpentEstimated < 0) background-color: #b0d878; @endif">
                        @if ($project->differenceSpentEstimated > 0)+{{ $project->differenceSpentEstimated }}j
                        @elseif ($project->differenceSpentEstimated < 0){{ $project->differenceSpentEstimated }}j
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
@elseif (isset($project_interface))
    <div class="no-entry">Aucune phase associée au projet.</div>
@endif