<div class="block last-tasks">
    <div class="block-content table-responsive">
        <h3>{{ trans('projectsquare::dashboard.last_tasks') }}</h3>

        @if ($is_client)
            <a href="{{ route('project_tasks', ['id' => $current_project->id]) }}" class="all pull-right"></a>
        @else
            <a href="{{ route('tasks_index') }}" class="all pull-right"></a>
        @endif
        <a href="{{ route('tasks_add') }}" class="add pull-right"></a>

        <a href="#" class="glyphicon glyphicon-move move-widget pull-right"></a>

        <table class="table table-striped">
            <thead>
            <tr>
                <!--<th>#</th>-->
                <th>{{ trans('projectsquare::tasks.title') }}</th>
                <th>{{ trans('projectsquare::tasks.allocated_user') }}</th>
                <th>{{ trans('projectsquare::tasks.status') }}</th>

                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <!-- <td>{{ $task->id }}</td> -->
                    <td style="border-left: 10px solid {{ $task->project->color }}">{{ $task->title }}</td>
                    <td>
                        @if (isset($task->allocated_user))
                            @include('projectsquare::includes.avatar', [
                                'id' => $task->allocated_user->id,
                                'name' => $task->allocated_user->complete_name
                            ])
                        @endif
                    </td>
                    <td>
                        @if ($task->status_id == 1){{ trans('projectsquare::tasks.to_do') }}
                        @elseif ($task->status_id == 2){{ trans('projectsquare::tasks.in_progress') }}
                        @elseif ($task->status_id == 3){{ trans('projectsquare::tasks.done') }}
                        @endif
                    </td>
                    <td align="right" class="action">
                        <a href="{{ route('tasks_edit', ['id' => $task->id]) }}" class="btn btn-sm btn-primary see-more"></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>