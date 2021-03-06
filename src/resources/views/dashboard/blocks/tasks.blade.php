<div class="block last-tasks" id="tasks-widget">
    <div class="block-content table-responsive">
        <h3>{{ __('projectsquare::dashboard.last_tasks') }}

            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.tasks')
            ])

            @if ($is_client)
                <a href="{{ route('project_tasks', ['id' => $current_project->id]) }}" class="all pull-right" title="{{ __('projectsquare::dashboard.tasks_list') }}"></a>
            @else
                <a href="{{ route('tasks_index') }}" class="all pull-right" title="{{ __('projectsquare::dashboard.tasks_list') }}"></a>
            @endif

            <a href="{{ route('tasks_add') }}" class="add pull-right" title="{{ __('projectsquare::dashboard.add_task') }}"></a>
            <a href="#" class="glyphicon glyphicon-move move-widget pull-right" title="{{ __('projectsquare::dashboard.move_widget') }}"></a>
        </h3>

        <table class="table table-striped">
            <thead>
            <tr>
                <th></th>
                <th>{{ __('projectsquare::tasks.title') }}</th>
                <th>{{ __('projectsquare::tasks.allocated_user') }}</th>
                <th>{{ __('projectsquare::tasks.status') }}</th>
                <th>{{ __('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td class="project-border" style="border-left: 10px solid @if (isset($task->project)) {{ $task->project->color }} @endif"></td>
                    <td>
                        <a href="{{ route('project_tasks_edit', ['uuid' => $task->project_id, 'task_uuid' => $task->id]) }}">
                            {{ $task->title }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('project_tasks_edit', ['uuid' => $task->project_id, 'task_uuid' => $task->id]) }}">
                            @if (isset($task->allocated_user))
                                @include('projectsquare::includes.avatar', [
                                    'id' => $task->allocated_user->id,
                                    'name' => $task->allocated_user->complete_name
                                ])
                            @else
                                <img class="default-avatar avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                            @endif
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('project_tasks_edit', ['uuid' => $task->project_id, 'task_uuid' => $task->id]) }}">
                            @if ($task->status_id == 1){{ __('projectsquare::tasks.to_do') }}
                            @elseif ($task->status_id == 2){{ __('projectsquare::tasks.in_progress') }}
                            @elseif ($task->status_id == 3){{ __('projectsquare::tasks.done') }}
                            @endif
                        </a>
                    </td>
                    <td align="right">
                        <a href="{{ route('project_tasks_edit', ['uuid' => $task->project_id, 'task_uuid' => $task->id]) }}" title="{{ __('projectsquare::dashboard.see_task') }}">
                            <i class="btn btn-sm btn-primary see-more"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
