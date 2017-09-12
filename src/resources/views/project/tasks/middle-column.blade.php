<div class="middle-column">
    @if (sizeof($phases) > 0)
        @foreach ($phases as $phase)
            <div class="parent">
                <div class="parent-wrapper">
                    <span class="name">{{ $phase->name }}</span>
                    <span class="childs-number">{{ sizeof($phase->tasks) }}</span>
                    <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
                </div>

                <div class="childs">
                    @foreach ($phase->tasks as $task)
                        <div class="child @if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)completed @endif @if (isset($currentTaskID) && $task->id == $currentTaskID)current @endif">
                            <div class="child-wrapper">
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
    @endif
    <div class="parent">
        <div class="parent-wrapper">
            <span class="name">Autres</span>
            <span class="childs-number">{{ sizeof($other_tasks) }}</span>
            <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
        </div>

        <div class="childs">
            @foreach ($other_tasks as $task)
                <div class="child @if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)completed @endif @if (isset($currentTaskID) && $task->id == $currentTaskID)current @endif">
                    <div class="child-wrapper">
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
</div>