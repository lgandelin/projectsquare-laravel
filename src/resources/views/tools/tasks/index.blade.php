@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates tasks-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::tasks.tasks_list') }}
                     @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.tasks')
                     ])
                </h1>
            </div>

            <form method="get">
                <div class="row">

                    <h2>{{ trans('projectsquare::tasks.filters.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="na">{{ trans('projectsquare::tasks.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                            <option value="na">{{ trans('projectsquare::tasks.filters.by_allocated_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_status" id="filter_status">
                            <option value="na">{{ trans('projectsquare::tasks.filters.by_status') }}</option>
                            @foreach ($task_statuses as $task_status)
                                <option value="{{ $task_status->id }}" @if ($filters['status'] == $task_status->id)selected="selected" @endif>{{ $task_status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <hr/>

            @if (isset($error))
                <div class="info bg-danger">
                    {{ $error }}
                </div>
            @endif

            @if (isset($confirmation))
                <div class="info bg-success">
                    {{ $confirmation }}
                </div>
            @endif

            <a href="{{ route('tasks_add') }}" class="create-button">
                {{ trans('projectsquare::tasks.add_task') }}

                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ trans('projectsquare::tasks.task') }}<a href="{{ route('tasks_index', ['sc' => 'title', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-alpha-{{ $sort_order }}"></i></a></th>
                        <th>{{ trans('projectsquare::tasks.phase') }}<a href="{{ route('tasks_index', ['sc' => 'phase_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                        <th>{{ trans('projectsquare::tasks.client') }}<a href="{{ route('tasks_index', ['sc' => 'client', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                        <th>{{ trans('projectsquare::tasks.allocated_user') }}<a href="{{ route('tasks_index', ['sc' => 'allocated_user_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                        <th>{{ trans('projectsquare::tasks.status') }}<a href="{{ route('tasks_index', ['sc' => 'status_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                        <th>{{ trans('projectsquare::tasks.estimated_time') }}</th>
                        <th>{{ trans('projectsquare::tasks.spent_time') }}</th>
                        <th>{{ trans('projectsquare::generic.action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td class="project-border" @if (isset($task->project))style="border-left: 10px solid {{ $task->project->color }}"@endif></td>
                            <td>
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    {{ $task->title }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    @if ($task->phase){{ $task->phase->name }}@endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    @if (isset($task->project) && isset($task->project->client)){{ $task->project->client->name }}@endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
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
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    @if ($task->status_id == 1)A faire
                                    @elseif ($task->status_id == 2)En cours
                                    @elseif ($task->status_id == 3)Terminé
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    @if ($task->estimated_time_days > 0){{ $task->estimated_time_days }} {{ trans('projectsquare::generic.days_abbr') }}@endif @if ($task->estimated_time_hours > 0){{ $task->estimated_time_hours }} {{ trans('projectsquare::generic.hours_abbr') }}@endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    @if ($task->spent_time_days > 0){{ $task->spent_time_days }} {{ trans('projectsquare::generic.days_abbr') }}@endif @if ($task->spent_time_hours > 0){{ $task->spent_time_hours }} {{ trans('projectsquare::generic.hours_abbr') }}@endif
                                </a>
                            </td>
                            <td width="10%" class="action" align="right">
                                <a href="{{ route('tasks_edit', ['id' => $task->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                                <a href="{{ route('tasks_delete', ['id' => $task->id]) }}">
                                    <i class="btn cancel btn-delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                @include('projectsquare::administration.includes.items_per_page')
                {!! $tasks->appends([
                    'filter_project' => $filters['project'],
                    'filter_allocated_user' => $filters['allocated_user'],
                    'filter_status' => $filters['status'],
                    'it' => $items_per_page,
                    'sc' => $sort_column,
                    'so' => $sort_order
                ])->links() !!}
            </div>
        </div>

    </div>
@endsection