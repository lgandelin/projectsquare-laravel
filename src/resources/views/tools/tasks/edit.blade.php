@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates add-task-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::tasks.edit_task') }}</h1>
                <a href="{{ $back_link }}" class="btn back"></a>
            </div>

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

            <form action="{{ route('tasks_update') }}" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">{{ trans('projectsquare::tasks.title') }}</label>
                            <input class="form-control" type="text" placeholder="{{ trans('projectsquare::tasks.title_placeholder') }}" name="title" value="{{{ $task->title }}}"/>
                        </div>

                        <div class="form-group">
                            <label for="project_id">{{ trans('projectsquare::tasks.project') }}</label>
                            @if (isset($projects))
                                <select class="form-control" name="project_id">
                                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" @if ((isset($task->projectID) && $task->projectID == $project->id))selected="selected"@endif>@if (isset($project->client))[{{ $project->client->name }}]@endif {{ $project->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ trans('projectsquare::tasks.no_project_yet') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="description">{{ trans('projectsquare::tasks.description') }}</label>
                            <textarea class="form-control" rows="12" placeholder="{{ trans('projectsquare::tasks.description') }}" name="description">@if (isset($task->description)){{ $task->description }}@endif</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn valid">
                                <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status_id">{{ trans('projectsquare::tasks.status') }}</label>
                            @if (isset($task_statuses))
                                <select class="form-control" name="status_id">
                                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($task_statuses as $task_status)
                                        <option value="{{ $task_status->id }}" @if (isset($task->statusID) && $task->statusID == $task_status->id) selected="selected" @endif>{{ $task_status->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ trans('projectsquare::tasks.no_task_status_yet') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="allocated_user_id">{{ trans('projectsquare::tasks.allocated_user') }}</label>
                            @if (isset($users))
                                <select class="form-control" name="allocated_user_id">
                                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (isset($task->allocatedUserID) && $task->allocatedUserID == $user->id) selected="selected" @endif>{{ $user->complete_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ trans('projectsquare::tasks.no_user_yet') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="estimated_time">{{ trans('projectsquare::tasks.estimated_time') }}</label><br>
                            <input class="form-control" type="text" name="estimated_time_days" style="display: inline-block; width: 5rem; margin-right: 0.5rem;" value="@if (isset($task->estimatedTimeDays)){{ $task->estimatedTimeDays }}@endif" /> {{ trans('projectsquare::generic.days') }}
                            <input class="form-control" type="text" name="estimated_time_hours" style="display: inline-block; width: 5rem; margin-left: 1rem; margin-right: 0.5rem;" value="@if (isset($task->estimatedTimeHours)){{ $task->estimatedTimeHours }}@endif" /> {{ trans('projectsquare::generic.hours') }}
                        </div>

                        <div class="form-group">
                            <label for="estimated_time">{{ trans('projectsquare::tasks.spent_time') }}</label><br>
                            <input class="form-control" type="text" name="spent_time_days" style="display: inline-block; width: 5rem; margin-right: 0.5rem;" value="@if (isset($task->spentTimeDays)){{ $task->spentTimeDays }}@endif" /> {{ trans('projectsquare::generic.days') }}
                            <input class="form-control" type="text" name="spent_time_hours" style="display: inline-block; width: 5rem; margin-left: 1rem; margin-right: 0.5rem;" value="@if (isset($task->spentTimeHours)){{ $task->spentTimeHours }}@endif" /> {{ trans('projectsquare::generic.hours') }}
                        </div>
                    </div>
                </div>

                @if (isset($logged_in_user))
                    <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
                @endif

                <input type="hidden" name="task_id" value="{{ $task->id }}" />

                {!! csrf_field() !!}
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection')
@endsection