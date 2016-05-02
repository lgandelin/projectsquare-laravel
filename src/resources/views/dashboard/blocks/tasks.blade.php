<div id="tasks" class="block">
    <h3>{{ trans('projectsquare::dashboard.tasks') }}</h3>
    <div class="block-content">
        <ul class="tasks">
            @foreach ($tasks as $task)
                <li class="task" data-id="{{ $task->id }}" data-status="{{ $task->status }}">
                    <span class="name @if($task->status == true)task-status-completed @endif">{{ $task->name }}</span>
                    <input type="hidden" name="id" value="{{ $task->id }}" />
                    <span class="glyphicon glyphicon-remove btn-delete-task"></span>
                </li>
            @endforeach
        </ul>

        <div class="form-inline">
            <div class="form-group">
                <label for="newtask">{{ trans('projectsquare::tasks.new-task') }}</label> :
                <input type="text" class="form-control new-task" name="new-task" autocomplete="off" />
                <input type="submit" class="btn btn-success btn-valid-create-task" value="{{ trans('projectsquare::generic.add') }}" />
            </div>
            <div class="alert alert-danger" style="display: none; margin-top: 2rem">
                <span class="text"></span>
            </div>
        </div>
    </div>
</div>