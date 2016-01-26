@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('tickets_index') }}">{{ trans('gateway::tickets.tickets_list') }}</a></li>
        <li class="active">{{ trans('gateway::tickets.edit_ticket') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::tickets.edit_ticket') }}</h1>
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

    <h3>{{ trans('gateway::tickets.ticket_data') }}</h3>
    <form action="{{ route('tickets_update_infos') }}" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">{{ trans('gateway::tickets.title') }}</label>
                    <input class="form-control" type="text" placeholder="{{ trans('gateway::tickets.title_placeholder') }}" name="title" @if (isset($ticket->title))value="{{ $ticket->title }}"@endif />
                </div>

                <div class="form-group">
                    <label for="project_id">{{ trans('gateway::tickets.project') }}</label>
                    @if (isset($projects))
                        <select class="form-control" name="project_id">
                            <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if (isset($ticket) && $ticket->project_id == $project->id)selected="selected"@endif>[{{ $project->client->name }}] {{ $project->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="info bg-info">{{ trans('gateway::tickets.no_project_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
                    <a href="{{ route('tickets_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type_id">{{ trans('gateway::tickets.type') }}</label>
                    @if (isset($ticket_types))
                        <select class="form-control" name="type_id">
                            <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                            @foreach ($ticket_types as $ticket_type)
                                <option value="{{ $ticket_type->id }}" @if (isset($ticket) && $ticket->type_id == $ticket_type->id)selected="selected"@endif>{{ $ticket_type->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="info bg-info">{{ trans('gateway::tickets.no_ticket_type_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('gateway::tickets.description') }}</label>
                    <textarea class="form-control" rows="4" placeholder="{{ trans('gateway::tickets.description') }}" name="description">@if (isset($ticket->description)){{ $ticket->description }}@endif</textarea>
                </div>
            </div>
        </div>

        @if (isset($ticket->id))
            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
        @endif

        {!! csrf_field() !!}
    </form>

    <p>&nbsp;</p>
    <h3>{{ trans('gateway::tickets.ticket_state') }}</h3>

    <form action="{{ route('tickets_update') }}" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status_id">{{ trans('gateway::tickets.status') }}</label>
                    @if (isset($ticket_status))
                    <select class="form-control" name="status_id">
                        <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                        @foreach ($ticket_status as $ticket_status)
                            <option value="{{ $ticket_status->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->status_id == $ticket_status->id)selected="selected"@endif>{{ $ticket_status->name }}</option>
                        @endforeach
                    </select>
                    @else
                        <div class="info bg-info">{{ trans('gateway::tickets.no_ticket_status_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="allocated_user_id">{{ trans('gateway::tickets.allocated_user') }}</label>
                    @if (isset($users))
                    <select class="form-control" name="allocated_user_id">
                        <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->allocated_user_id == $user->id)selected="selected"@endif>{{ $user->first_name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                    @else
                        <div class="info bg-info">{{ trans('gateway::tickets.no_user_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="comments">{{ trans('gateway::tickets.comments') }}</label>
                    <textarea class="form-control" rows="4" placeholder="{{ trans('gateway::tickets.comments') }}" name="comments"></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
                    <a href="{{ route('tickets_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">{{ trans('gateway::tickets.due_date') }}</label>
                    <input class="form-control datepicker" type="text" placeholder="{{ trans('gateway::tickets.due_date_placeholder') }}" name="due_date" @if (isset($ticket->states[0]->due_date))value="{{ $ticket->states[0]->due_date }}"@endif autocomplete="off" />
                </div>

                <div class="form-group">
                    <label for="priority">{{ trans('gateway::tickets.priority') }}</label>
                    <select class="form-control" name="priority">
                        <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->priority == $i)selected="selected"@endif>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            @if (isset($ticket->id))
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
            @endif

            @if (isset($logged_in_user))
                <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
            @endif

            {!! csrf_field() !!}
        </div>
    </form>

    <p>&nbsp;</p>

    <h3>{{ trans('gateway::tickets.ticket_history') }}</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <th>{{ trans('gateway::generic.creation_date') }}</th>
            <th>{{ trans('gateway::tickets.author_user') }}</th>
            <th>{{ trans('gateway::tickets.allocated_user') }}</th>
            <th>{{ trans('gateway::tickets.due_date') }}</th>
            <th>{{ trans('gateway::tickets.priority') }}</th>
            <th>{{ trans('gateway::tickets.status') }}</th>
            <th>{{ trans('gateway::tickets.comments') }}</th>
        </thead>
        <tbody>
            @foreach ($ticket->states as $i => $ticket_state)
                <tr>
                    <td>{{ date('d/m/Y H:i', strtotime($ticket_state->created_at)) }}</td>
                    <td>@if ($ticket_state->author_user){{ $ticket_state->author_user->first_name }} {{ $ticket_state->author_user->last_name }}@endif</td>
                    <td>@if ($ticket_state->allocated_user){{ $ticket_state->allocated_user->first_name }} {{ $ticket_state->allocated_user->last_name }}@endif</td>
                    <td>@if ($ticket_state->due_date){{ $ticket_state->due_date }}</span>@endif</td>
                    <td><span class="badge priority{{ $ticket_state->priority }}">{{ $ticket_state->priority }}</span></td>
                    <td>@if ($ticket_state->status)<span class="label label-primary">{{ $ticket_state->status->name }}</span>@endif</td>
                    <td>{{ $ticket_state->comments }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection