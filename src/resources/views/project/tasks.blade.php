@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tasks'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.tasks') }}</h1>

            <form method="get">
                <div class="row">

                    <h2>{{ trans('projectsquare::tasks.filters.filters') }}</h2>

                    @if (!$is_client)
                        <div class="form-group col-md-2">
                            <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                                <option value="">{{ trans('projectsquare::tasks.filters.by_allocated_user') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_status" id="filter_status">
                            <option value="">{{ trans('projectsquare::tasks.filters.by_status') }}</option>
                            @foreach ($task_statuses as $task_status)
                                <option value="{{ $task_status->id }}" @if ($filters['status'] == $task_status->id)selected="selected" @endif>{{ $task_status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input class="btn button" type="submit" value="{{ trans('projectsquare::generic.valid') }}" />
                    </div>
                </div>
            </form>

            <hr/>

            <a href="{{ route('tasks_add') }}" class="btn pull-right add"></a>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('projectsquare::tasks.task') }}</th>
                    <th>{{ trans('projectsquare::tasks.allocated_user') }}</th>
                    <th>{{ trans('projectsquare::tasks.status') }}</th>
                    <th>{{ trans('projectsquare::tasks.estimated_time') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
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
                        <td>@if ($task->estimated_time_days > 0){{ $task->estimated_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($task->estimated_time_hours > 0){{ $task->estimated_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</td>
                        <td align="right">
                            <a href="{{ route('tasks_edit', ['id' => $task->id]) }}" class="btn see-more"></a>
                            <a href="{{ route('tasks_delete', ['id' => $task->id]) }}" class="btn cancel btn-delete"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection