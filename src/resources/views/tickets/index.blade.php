@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::tickets.tickets_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::tickets.tickets_list') }}</h1>
    </div>

    <form method="get">
        <div class="row">
            <div class="form-group col-md-2">
                <label for="filter_project">{{ trans('projectsquare::tickets.filters.by_project') }}</label>
                <select class="form-control" name="filter_project" id="filter_project">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>{{ $project->client->name }} - {{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="filter_allocated_user">{{ trans('projectsquare::tickets.filters.by_allocated_user') }}</label>
                <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="filter_status">{{ trans('projectsquare::tickets.filters.by_status') }}</label>
                <select class="form-control" name="filter_status" id="filter_status">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($ticket_statuses as $ticket_status)
                        <option value="{{ $ticket_status->id }}" @if ($filters['status'] == $ticket_status->id)selected="selected" @endif>{{ $ticket_status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="filter_type">{{ trans('projectsquare::tickets.filters.by_type') }}</label>
                <select class="form-control" name="filter_type" id="filter_type">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($ticket_types as $ticket_type)
                        <option value="{{ $ticket_type->id }}" @if ($filters['type'] == $ticket_type->id)selected="selected" @endif>{{ $ticket_type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input class="btn btn-success" type="submit" value="{{ trans('projectsquare::generic.valid') }}" style="margin-top: 2.5rem"/>
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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                    <th>{{ trans('projectsquare::tickets.client') }}</th>
                    <th>{{ trans('projectsquare::tickets.project') }}</th>
                    <th>{{ trans('projectsquare::tickets.type') }}</th>
                    <th>{{ trans('projectsquare::tickets.author_user') }}</th>
                    <th>{{ trans('projectsquare::tickets.allocated_user') }}</th>
                    <th>{{ trans('projectsquare::tickets.status') }}</th>
                    <th>{{ trans('projectsquare::tickets.priority') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td><span class="label" style="background: {{ $ticket->project->color }}">{{ $ticket->project->client->name }}</span></td>
                        <td>{{ $ticket->project->name }}</td>
                        <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                        <td>@if (isset($ticket->states[count($ticket->states) - 1])){{ $ticket->states[count($ticket->states) - 1]->author_user->complete_name }}@endif</td>
                        <td>@if (isset($ticket->last_state) && $ticket->last_state->allocated_user){{ $ticket->last_state->allocated_user->complete_name }}@endif</td>
                        <td>@if (isset($ticket->last_state) && $ticket->last_state->status)<span class="status status-{{ $ticket->last_state->status->id }}">{{ $ticket->last_state->status->name}}</span>@endif</td>
                        <td>@if (isset($ticket->last_state))<span class="badge priority-{{ $ticket->last_state->priority }}">{{ $ticket->last_state->priority }}</span>@endif</td>
                        <td>
                            <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                            <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center">
        {!! $tickets->render() !!}
    </div>

    <a href="{{ route('tickets_add') }}" class="btn btn-success btn-create-ticket"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::tickets.add_ticket') }}</a>
@endsection