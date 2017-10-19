@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tasks'])

    <div class="templates project-tasks-template">
        @include('projectsquare::project.tasks.middle-column', ['currentTaskID' => $task->id])

        <div class="content-page">
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
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <h2>{{ __('projectsquare::tasks.description') }}</h2>
                            <textarea class="form-control" rows="12" placeholder="{{ __('projectsquare::tasks.description') }}" name="description">@if (isset($task->description)){{ $task->description }}@endif</textarea>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <h2>{{ __('projectsquare::tasks.general_infos') }}</h2>

                        <div class="form-group">
                            <label for="title">{{ __('projectsquare::tasks.title') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('projectsquare::tasks.title_placeholder') }}" name="title" value="{{{ $task->title }}}"/>
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
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: inline-block">
                        <label for="estimated_time">{{ __('projectsquare::tasks.estimated_time') }}</label><br/>
                        <input class="form-control" type="text" name="estimated_time_days" style="display: inline-block; width: 6rem; margin-right: 0.5rem;" value="@if (isset($task->estimatedTimeDays)){{ $task->estimatedTimeDays }}@endif" /> {{ __('projectsquare::generic.days') }}
                    </div>

                    <div style="display: inline-block; margin-left: 1rem;">
                        <label for="estimated_time">{{ __('projectsquare::tasks.spent_time') }}</label><br/>
                        <input class="form-control" type="text" name="spent_time_days" style="display: inline-block; width: 6rem; margin-right: 0.5rem;" value="@if (isset($task->spentTimeDays)){{ $task->spentTimeDays }}@endif" /> {{ __('projectsquare::generic.days') }}
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
