@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates add-task-template">
            <div class="page-header">
                <h1>{{ __('projectsquare::tasks.edit_task') }}</h1>
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
                            <label for="title">{{ __('projectsquare::tasks.title') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('projectsquare::tasks.title_placeholder') }}" name="title" value="{{{ $task->title }}}"/>
                        </div>

                        <div class="form-group">
                            <label for="project_id">{{ __('projectsquare::tasks.project') }}</label>
                            @if (isset($projects))
                                <select class="form-control" name="project_id">
                                    <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" @if ((isset($task->projectID) && $task->projectID == $project->id))selected="selected"@endif>@if (isset($project->client))[{{ $project->client->name }}]@endif {{ $project->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ __('projectsquare::tasks.no_project_yet') }}</div>
                            @endif
                        </div>

                        @if (isset($task->phaseName))
                            <div class="form-group">
                                <label for="phase_id">{{ __('projectsquare::tasks.phase') }}</label>
                                <input class="form-control" type="text" disabled value="{{ $task->phaseName }}">
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="description">{{ __('projectsquare::tasks.description') }}</label>
                            <textarea class="form-control" rows="12" placeholder="{{ __('projectsquare::tasks.description') }}" name="description">@if (isset($task->description)){{ $task->description }}@endif</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status_id">{{ __('projectsquare::tasks.status') }}</label>
                            @if (isset($task_statuses))
                                <select class="form-control" name="status_id">
                                    <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($task_statuses as $task_status)
                                        <option value="{{ $task_status->id }}" @if (isset($task->statusID) && $task->statusID == $task_status->id) selected="selected" @endif>{{ $task_status->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ __('projectsquare::tasks.no_task_status_yet') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="allocated_user_id">{{ __('projectsquare::tasks.allocated_user') }}</label>
                            @if (isset($users))
                                <select class="form-control" name="allocated_user_id">
                                    <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (isset($task->allocatedUserID) && $task->allocatedUserID == $user->id) selected="selected" @endif>{{ $user->complete_name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ __('projectsquare::tasks.no_user_yet') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div style="display: inline-block;">
                                <label for="estimated_time" style="display: inline-block">{{ __('projectsquare::tasks.estimated_time') }}</label><br>
                                <input class="form-control" type="text" name="estimated_time_days" style="display: inline-block; width: 6rem; margin-right: 0.5rem;" value="@if (isset($task->estimatedTimeDays)){{ $task->estimatedTimeDays }}@endif" /> {{ __('projectsquare::generic.days') }}
                            </div>

                            <div style="display: inline-block; margin-left: 5rem;">
                                <label for="estimated_time" style="display: inline-block">{{ __('projectsquare::tasks.spent_time') }}</label><br>
                                <input class="form-control" type="text" name="spent_time_days" style="display: inline-block; width: 6rem; margin-right: 0.5rem;" value="@if (isset($task->spentTimeDays)){{ $task->spentTimeDays }}@endif" /> {{ __('projectsquare::generic.days') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn valid">
                        <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
                    </button>
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