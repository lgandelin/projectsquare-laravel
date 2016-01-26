@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('tickets_index') }}">{{ trans('gateway::tickets.tickets_list') }}</a></li>
        <li class="active">{{ trans('gateway::tickets.add_ticket') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::tickets.add_ticket') }}</h1>
    </div>

    <form action="{{ route('tickets_store') }}" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">{{ trans('gateway::tickets.title') }}</label>
                    <input class="form-control" @if (isset($ticket) && $ticket->id) disabled @endif type="text" placeholder="{{ trans('gateway::tickets.title_placeholder') }}" name="title" @if (isset($ticket_title))value="{{ $ticket_title }}"@endif />
                </div>

                <div class="form-group">
                    <label for="project_id">{{ trans('gateway::tickets.project') }}</label>
                    @if (isset($projects))
                        <select class="form-control" name="project_id" @if (isset($ticket) && $ticket->id) disabled @endif>
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
                    <label for="description">{{ trans('gateway::tickets.description') }}</label>
                    <textarea class="form-control" @if (isset($ticket) && $ticket->id) disabled @else rows="10" @endif placeholder="{{ trans('gateway::tickets.description') }}" name="description">@if (isset($ticket_description)){{ $ticket_description }}@endif</textarea>
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
                    <label for="title">{{ trans('gateway::tickets.due_date') }}</label>
                    <input class="form-control datepicker" type="text" placeholder="{{ trans('gateway::tickets.due_date_placeholder') }}" name="due_date" @if (isset($ticket_due_date))value="{{ $ticket_due_date }}"@endif autocomplete="off" />
                </div>

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
                            <option value="{{ $user->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->allocated_user_id == $user->id)selected="selected"@endif>{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="info bg-info">{{ trans('gateway::tickets.no_user_yet') }}</div>
                    @endif
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
        </div>

        @if (isset($logged_in_user))
          <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
        @endif

        {!! csrf_field() !!}
    </form>
@endsection