@extends('projectsquare::default')

@section('content')
        <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::tasks.tasks_list') }}</li>
    </ol>-->
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
                        <option value="">{{ trans('projectsquare::tasks.filters.by_project') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                        <option value="">{{ trans('projectsquare::tasks.filters.by_allocated_user') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                        @endforeach
                    </select>
                </div>

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

        <div class="table-responsive">
            <a href="{{ route('tasks_add') }}" class="btn pull-right add create-task"></a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('projectsquare::tasks.task') }}</th>
                    <th>{{ trans('projectsquare::tasks.client') }}</th>
                    <th>{{ trans('projectsquare::tasks.allocated_user') }}</th>
                    <th>{{ trans('projectsquare::tasks.status') }}</th>
                    <th>{{ trans('projectsquare::tasks.estimated_time') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td @if (isset($task->project))style="border-left: 10px solid {{ $task->project->color }}"@endif></td>
                        <td>{{ $task->title }}</td>
                        <td>@if (isset($task->project)){{ $task->project->client->name }}@endif</td>
                        <td>
                            @if (isset($task->allocated_user))
                                @include('projectsquare::includes.avatar', [
                                    'id' => $task->allocated_user->id,
                                    'name' => $task->allocated_user->complete_name
                                ])
                            @endif
                        </td>
                        <td>
                            @if ($task->status_id == 1)A faire
                            @elseif ($task->status_id == 2)En cours
                            @elseif ($task->status_id == 3)Terminé
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

        <div class="text-center">
            {!! $tasks->render() !!}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection', ['select_project_name' => 'filter_project', 'select_user_name' => 'filter_allocated_user'])
@endsection