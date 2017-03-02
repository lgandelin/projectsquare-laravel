@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates tickets-template">
            <div class="page-header">
                <h1>{{ trans('projectsquare::tickets.tickets_list') }}
                     @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.tickets')
                  ])
                </h1>
            </div>

            <form method="get">
                <div class="row">

                    <h2> {{ trans('projectsquare::tasks.filters.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_allocated_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_status" id="filter_status">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_status') }}</option>
                            @foreach ($ticket_statuses as $ticket_status)
                                <option value="{{ $ticket_status->id }}" @if ($filters['status'] == $ticket_status->id)selected="selected" @endif>{{ $ticket_status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_type" id="filter_type">
                            <option value="">{{ trans('projectsquare::tickets.filters.by_type') }}</option>
                            @foreach ($ticket_types as $ticket_type)
                                <option value="{{ $ticket_type->id }}" @if ($filters['type'] == $ticket_type->id)selected="selected" @endif>{{ $ticket_type->name }}</option>
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
            <a href="{{ route('tickets_add') }}" class="btn pull-right add create-ticket"></a>
            <div class="table-responsive">
               
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ trans('projectsquare::tickets.priority') }}</th>
                            <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                            <th>{{ trans('projectsquare::tickets.client') }}</th>
                            <th>{{ trans('projectsquare::tickets.type') }}</th>
                            <th>{{ trans('projectsquare::tickets.author_user') }}</th>
                            <th>{{ trans('projectsquare::tickets.allocated_user') }}</th>
                            <th>{{ trans('projectsquare::tickets.status') }}</th>
                            <th>{{ trans('projectsquare::tickets.estimated_time') }}</th>
                            <th>{{ trans('projectsquare::tickets.spent_time') }}</th>
                            <th>{{ trans('projectsquare::generic.action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td class="priorities" style="border-left: 10px solid {{ $ticket->project->color }}"></td>
                                <td>@if (isset($ticket->last_state))<span class="priority priority-{{ $ticket->last_state->priority }}" title="{{ trans('projectsquare::generic.priority-' . $ticket->last_state->priority) }}"></span>@endif</td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ $ticket->project->client->name }}</td>
                                <td>@if (isset($ticket->type)){{ $ticket->type->name }}@endif</td>
                                <td>
                                    @if (isset($ticket->states[count($ticket->states) - 1]))
                                        @include('projectsquare::includes.avatar', [
                                            'id' => $ticket->states[count($ticket->states) - 1]->author_user->id,
                                            'name' => $ticket->states[count($ticket->states) - 1]->author_user->complete_name
                                        ])
                                    @endif
                                </td>
                                <td>
                                    @if (isset($ticket->last_state) && $ticket->last_state->allocated_user)
                                        @include('projectsquare::includes.avatar', [
                                            'id' => $ticket->last_state->allocated_user->id,
                                            'name' => $ticket->last_state->allocated_user->complete_name
                                        ])
                                    @endif
                                </td>
                                <td>@if (isset($ticket->last_state) && $ticket->last_state->status)<span class="status status-{{ $ticket->last_state->status->id }}">{{ $ticket->last_state->status->name}}</span>@endif</td>
                                <td>@if (isset($ticket->last_state) && $ticket->last_state->estimated_time_days > 0){{ $ticket->last_state->estimated_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if (isset($ticket->last_state) && $ticket->last_state->estimated_time_hours > 0){{ $ticket->last_state->estimated_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</td>
                                <td>@if (isset($ticket->last_state) && $ticket->last_state->spent_time_days > 0){{ $ticket->last_state->spent_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if (isset($ticket->last_state) && $ticket->last_state->spent_time_hours > 0){{ $ticket->last_state->spent_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</td>
                                <td align="right">
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn see-more"></a>
                                    <a href="#" class="glyphicon glyphicon-move move-widget"></a>
                                    
                                    <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn cancel btn-delete"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                {!! $tickets->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection', ['select_project_name' => 'filter_project', 'select_user_name' => 'filter_allocated_user'])
@endsection