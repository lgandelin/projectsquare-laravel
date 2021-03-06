@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::tickets.add_ticket') }}</h1>
                <a href="{{ $back_link }}" class="btn back"></a>
            </div>

            @if (isset($error))
                <div class="info bg-danger">
                    {{ $error }}
                </div>
            @endif

            <h3>{{ __('projectsquare::tickets.ticket_data') }}</h3>

            <form action="{{ route('tickets_store') }}" method="post">
                <div class="row ticket-infos">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">{{ __('projectsquare::tickets.title') }}</label>
                            <input class="form-control" type="text" placeholder="{{ __('projectsquare::tickets.title_placeholder') }}" name="title" value="{{{ $data['title'] }}}" />
                        </div>

                        @if ($is_client)
                            <div class="form-group">
                                <label for="project_id">{{ __('projectsquare::tickets.project') }}</label>
                                @if (isset($projects))
                                    <select class="form-control" disabled>
                                        <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @if ((isset($data['projectID']) && $data['projectID'] == $project->id) || ($current_project_id == $project->id))selected="selected"@endif>@if (isset($project->client))[{{ $project->client->name }}]@endif {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="project_id" value="{{ $current_project_id }}" />
                                @else
                                    <div class="info bg-info">{{ __('projectsquare::tickets.no_project_yet') }}</div>
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label for="project_id">{{ __('projectsquare::tickets.project') }}</label>
                                @if (isset($projects))
                                    <select class="form-control" name="project_id" @if (isset($ticket) && $ticket->id) disabled @endif>
                                        <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @if ((isset($data['projectID']) && $data['projectID'] == $project->id) || ($current_project_id == $project->id))selected="selected"@endif>@if (isset($project->client))[{{ $project->client->name }}]@endif {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="info bg-info">{{ __('projectsquare::tickets.no_project_yet') }}</div>
                                @endif
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="type_id">{{ __('projectsquare::tickets.type') }} {{ $data['typeID'] }}</label>
                            @if (isset($ticket_types))
                                <select class="form-control" name="type_id">
                                    <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($ticket_types as $ticket_type)
                                        <option value="{{ $ticket_type->id }}" @if (isset($data['typeID']) && $data['typeID'] == $ticket_type->id) selected="selected" @endif>{{ $ticket_type->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ __('projectsquare::tickets.no_ticket_type_yet') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">{{ __('projectsquare::tickets.description') }}</label>
                            <textarea class="form-control" rows="4" placeholder="{{ __('projectsquare::tickets.description') }}" name="description">{{{ $data['description'] }}}</textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <h3>{{ __('projectsquare::tickets.ticket_state') }}</h3>

                <div class="row ticket-state">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status_id">{{ __('projectsquare::tickets.status') }}</label>
                            @if (isset($ticket_statuses))
                                <select class="form-control" name="status_id" @if ($is_client){{ 'disabled' }}@endif>
                                    <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                    @foreach ($ticket_statuses as $i => $ticket_status)
                                        <option value="{{ $ticket_status->id }}" @if ((isset($data['statusID']) && $data['statusID'] == $ticket_status->id) || $is_client && $i == 0) selected="selected" @endif>{{ $ticket_status->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="info bg-info">{{ __('projectsquare::tickets.no_ticket_status_yet') }}</div>
                            @endif

                            @if ($is_client)
                                <input type="hidden" name="status_id" value="{{ $ticket_statuses[0]->id }}" />
                            @endif
                        </div>

                        @if (!$is_client)
                            <div class="form-group">
                                <label for="allocated_user_id">{{ __('projectsquare::tickets.allocated_user') }}</label>
                                @if (isset($users))
                                    <select class="form-control" name="allocated_user_id">
                                        <option value="0">{{ __('projectsquare::generic.choose_value') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if (isset($data['allocatedUserID']) && $data['allocatedUserID'] == $user->id) selected="selected" @endif>{{ $user->complete_name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="info bg-info">{{ __('projectsquare::tickets.no_user_yet') }}</div>
                                @endif
                            </div>
                        @endif

                        @if (!$is_client)
                            <div class="form-group">
                                <label for="estimated_time">{{ __('projectsquare::tasks.estimated_time') }}</label><br>
                                <input class="form-control" type="text" name="estimated_time_hours" style="display: inline-block; width: 6rem; margin-right: 0.5rem;" value="{{{ $data['estimatedTimeHours'] }}}" /> {{ __('projectsquare::generic.hours') }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">

                        @if (!$is_client)
                            <div class="form-group">
                                <label for="title">{{ __('projectsquare::tickets.due_date') }}</label>
                                <input class="form-control datepicker" type="text" placeholder="{{ __('projectsquare::tickets.due_date_placeholder') }}" name="due_date" autocomplete="off" />
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="priority">{{ __('projectsquare::tickets.priority') }}</label>
                            <select class="form-control" name="priority">
                                <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                                @for ($i = 1; $i <= 3; $i++)
                                    <option value="{{ $i }}" @if (isset($data['priority']) && $i == $data['priority'])selected="selected"@endif>{{ __('projectsquare::generic.priority-' . $i) }}</option>
                                @endfor
                            </select>
                        </div>

                    </div>
                </div>

                @if (isset($logged_in_user))
                  <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
                @endif

                <div class="form-group" style="margin-top:2rem">
                    <button type="submit" class="btn valid">
                        <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
                    </button>
                </div>

                {!! csrf_field() !!}
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection')
@endsection