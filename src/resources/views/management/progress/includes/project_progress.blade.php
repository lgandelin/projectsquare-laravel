@if (sizeof($project->phases) > 0)
    <div class="col">
        <div class="project">
            @if (!isset($project_interface))<div class="header" style="background:{{ $project->color }}">{{ $project->name }}</div>@endif
            <table class="table table-bordered">
                <tr>
                    <td>{{ __('projectsquare::progress.phases_tasks') }}</td>
                    <td>{{ __('projectsquare::progress.progress') }}</td>
                </tr>

                @foreach ($project->phases as $phase)
                    <tr>
                        <td class="phase">
                            <span class="name">
                                {{ $phase->name }}
                                <!-- @if ($phase->estimatedDuration > 0)<span class="duration">{{ $phase->estimatedDuration }} jour(s)</span>@endif -->
                            </span>

                            <div class="tasks">
                                @foreach ($phase->tasks as $task)
                                    <div class="task" style="@if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)background-color: #5497aa; @endif">
                                        <div class="description">

                                            @if (isset($task->allocatedUser))
                                                @include('projectsquare::includes.avatar', [
                                                    'id' => $task->allocatedUser->id,
                                                    'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                                ])
                                            @endif

                                            <span class="name">{{ $task->title }}</span>
                                            @if ($task->estimatedTimeDays > 0)<span class="duration"> <strong>Temps estimé :</strong> {{ $task->estimatedTimeDays }} jour(s)</span> @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="phase-value" width="20%">
                            @if ($phase->progress !== null && $phase->progress !== ""){{ $phase->progress }} %@endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="text-align: right;">Total</td>
                    <td width="20%" class="total">@if ($project->progress !== null && $project->progress !== ""){{ $project->progress }} %@endif</td>
                </tr>
            </table>
        </div>
    </div>
@elseif (isset($project_interface))
    <div class="no-entry">Aucune phase associée au projet.</div>
@endif