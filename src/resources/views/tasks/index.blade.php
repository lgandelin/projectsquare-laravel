@extends('projectsquare::default')

@section('content')
        <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::tasks.tasks_list') }}</li>
    </ol>-->
<div class="content-page">
    <div class="templates tasks-template">
        <div class="page-header">
            <h1>{{ trans('projectsquare::tasks.tasks_list') }}</h1>
        </div>

        <form method="get">
            <div class="row">

                <h2>Filtres</h2>

                <div class="form-group col-md-2">
                    <select class="form-control" name="filter_project" id="filter_project">
                        <option value="">{{ trans('projectsquare::tasks.filters.by_project') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>{{ $project->client->name }} - {{ $project->name }}</option>
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
                    <th>#</th>
                    <th>{{ trans('projectsquare::tasks.task') }}</th>
                    <th>{{ trans('projectsquare::tasks.client') }}</th>
                    <th>{{ trans('projectsquare::tasks.allocated_user') }}</th>
                    <th>{{ trans('projectsquare::tasks.status') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="priorities" style="border-left: 10px solid {{ $task->project->color }}"></td>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->project->client->name }}</td>
                        <td>
                            @if (isset($task->last_state) && $task->last_state->allocated_user)
                                @include('projectsquare::includes.avatar', [
                                    'id' => $task->allocated_user->id,
                                    'name' => $task->allocated_user->complete_name
                                ])
                            @endif
                        </td>
                        <td>{{ $task->status }}</td>
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
</div>
@endsection