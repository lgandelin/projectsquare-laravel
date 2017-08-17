<div class="middle-column">
    @if (sizeof($phases) > 0)
        <div class="phases">
            @foreach ($phases as $phase)
                <div class="phase">
                    <div class="phase-wrapper">
                        <span class="name">{{ $phase->name }}</span>
                        <span class="tasks-number">{{ sizeof($phase->tasks) }}</span>
                        <span class="glyphicon glyphicon-triangle-top toggle-tasks"></span>
                    </div>

                    <div class="tasks">
                        @foreach ($phase->tasks as $task)
                            <div class="task @if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)completed @endif @if (isset($currentTaskID) && $task->id == $currentTaskID)current @endif">
                                <div class="task-wrapper">
                                    <a href="{{ route('project_tasks_edit', ['uuid' => $project->id, 'task_uuid' => $task->id]) }}">
                                        @if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)
                                            <i class="fa fa-check task-check"></i>
                                        @endif

                                        @if (isset($task->allocatedUser))
                                            @include('projectsquare::includes.avatar', [
                                                'id' => $task->allocatedUser->id,
                                                'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                            ])
                                        @else
                                            <img class="avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                                        @endif
                                        <span class="name">{{ $task->title }}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>