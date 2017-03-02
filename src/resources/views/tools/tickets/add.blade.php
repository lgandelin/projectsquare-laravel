@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::tickets.add_ticket') }}</h1>
                <a href="{{ $back_link }}" class="btn back"></a>
            </div>

            @if (isset($error))
                <div class="info bg-danger">
                    {{ $error }}
                </div>
            @endif

            <form action="{{ route('tickets_store') }}" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">{{ trans('projectsquare::tickets.title') }}</label>
                            <input class="form-control" type="text" placeholder="{{ trans('projectsquare::tickets.title_placeholder') }}" name="title" value="{{{ $data['title'] }}}"/>
                        </div>

                        @if ($is_client)
                            <div class="form-group">
                                <label for="project_id">{{ trans('projectsquare::tickets.project') }}</label>
                                @if (isset($projects))
                                    <select class="form-control" disabled>
                                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @if ((isset($data['projectID']) && $data['projectID'] == $project->id) || ($current_project_id == $project->id))selected="selected"@endif>[{{ $project->client->name }}] {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="project_id" value="{{ $current_project_id }}" />
                                @else
                                    <div class="info bg-info">{{ trans('projectsquare::tickets.no_project_yet') }}</div>
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label for="project_id">{{ trans('projectsquare::tickets.project') }}</label>
                                @if (isset($projects))
                                    <select class="form-control" name="project_id" @if (isset($ticket) && $ticket->id) disabled @endif>
                                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" @if ((isset($data['projectID']) && $data['projectID'] == $project->id) || ($current_project_id == $project->id))selected="selected"@endif>[{{ $project->client->name }}] {{ $project->name }}</option>
                                    @endforeach
                                    </select>
                                @else
                                    <div class="info bg-info">{{ trans('projectsquare::tickets.no_project_yet') }}</div>
                                @endif
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="description">{{ trans('projectsquare::tickets.description') }}</label>
                            <textarea class="form-control" rows="12" placeholder="{{ trans('projectsquare::tickets.description') }}" name="description">{{{ $data['description'] }}}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn valid">
                                <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type_id">{{ trans('projectsquare::tickets.type') }}</label>
                            @if (isset($ticket_types))
                                <select class="form-control" name="type_id">
                                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($ticket_types as $ticket_type)
                                    <option value="{{ $ticket_type->id }}" @if (isset($data['typeID']) && $data['typeID'] == $ticket_type->id) selected="selected" @endif>{{ $ticket_type->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ trans('projectsquare::tickets.no_ticket_type_yet') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="title">{{ trans('projectsquare::tickets.due_date') }}</label>
                            <input class="form-control datepicker" type="text" placeholder="{{ trans('projectsquare::tickets.due_date_placeholder') }}" name="due_date" autocomplete="off" value="@if (isset($data['dueDate']) && $data['dueDate'] instanceof \DateTime){{ $data['dueDate']->format('d/m/Y') }}@endif" />
                        </div>

                        @if (!$is_client)
                            <div class="form-group">
                                <label for="status_id">{{ trans('projectsquare::tickets.status') }}</label>
                                @if (isset($ticket_status))
                                    <select class="form-control" name="status_id">
                                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($ticket_status as $ticket_status)
                                            <option value="{{ $ticket_status->id }}" @if (isset($data['statusID']) && $data['statusID'] == $ticket_status->id) selected="selected" @endif>{{ $ticket_status->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="info bg-info">{{ trans('projectsquare::tickets.no_ticket_status_yet') }}</div>
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label for="status_id">{{ trans('projectsquare::tickets.status') }}</label>
                                @if (isset($ticket_status))
                                    <select class="form-control" disabled autocomplete="off">
                                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($ticket_status as $ticket_status)
                                            <option value="{{ $ticket_status->id }}" @if ($ticket_status->id == 1)selected="selected"@endif>{{ $ticket_status->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="status_id" value="1" />
                                @else
                                    <div class="info bg-info">{{ trans('projectsquare::tickets.no_ticket_status_yet') }}</div>
                                @endif
                            </div>
                        @endif

                        @if (!$is_client)
                            <div class="form-group">
                                <label for="allocated_user_id">{{ trans('projectsquare::tickets.allocated_user') }}</label>
                                @if (isset($users))
                                    <select class="form-control" name="allocated_user_id">
                                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (isset($data['allocatedUserID']) && $data['allocatedUserID'] == $user->id) selected="selected" @endif>{{ $user->complete_name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="info bg-info">{{ trans('projectsquare::tickets.no_user_yet') }}</div>
                                @endif
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="priority">{{ trans('projectsquare::tickets.priority') }}</label>
                            <select class="form-control" name="priority">
                                <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                                @for ($i = 1; $i <= 3; $i++)
                                    <option value="{{ $i }}" @if (isset($data['priority']) && $data['priority'] == $i) selected="selected" @endif>{{ trans('projectsquare::generic.priority-' . $i) }}</option>
                                @endfor
                            </select>
                        </div>

                        @if (!$is_client)
                            <div class="form-group">
                                <label for="estimated_time">{{ trans('projectsquare::tasks.estimated_time') }}</label><br>
                                <input class="form-control" type="text" name="estimated_time_days" style="display: inline-block; width: 5rem; margin-right: 0.5rem;" value="{{{ $data['estimatedTimeDays'] }}}" /> {{ trans('projectsquare::generic.days') }}
                                <input class="form-control" type="text" name="estimated_time_hours" style="display: inline-block; width: 5rem; margin-left: 1rem; margin-right: 0.5rem;" value="{{{ $data['estimatedTimeHours'] }}}" /> {{ trans('projectsquare::generic.hours') }}
                            </div>

                            <div class="form-group" style="display:none">
                                <label for="spent_time">{{ trans('projectsquare::tasks.spent_time') }}</label><br>
                                <input class="form-control" type="text" name="spent_time_days" /> {{ trans('projectsquare::generic.days') }}
                                <input class="form-control" type="text" name="spent_time_hours" /> {{ trans('projectsquare::generic.hours') }}
                            </div>
                        @endif
                    </div>
                </div>

                @if (isset($logged_in_user))
                  <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
                @endif

                {!! csrf_field() !!}
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection')
@endsection