@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tickets'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.tickets') }}
                @include('projectsquare::includes.tooltip', [
                    'text' => trans('projectsquare::tooltips.tickets')
                ])
            </h1>

            <form method="get">
                <div class="row">

                    <h2>{{ trans('projectsquare::tickets.filters.filters') }}</h2>

                    @if (!$is_client)
                        <div class="form-group col-md-2">
                            <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                                <option value="na">{{ trans('projectsquare::tickets.filters.by_allocated_user') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_status" id="filter_status">
                            <option value="na">{{ trans('projectsquare::tickets.filters.by_status') }}</option>
                            @foreach ($ticket_statuses as $ticket_status)
                                <option value="{{ $ticket_status->id }}" @if ($filters['status'] == $ticket_status->id)selected="selected" @endif>{{ $ticket_status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_type" id="filter_type">
                            <option value="na">{{ trans('projectsquare::tickets.filters.by_type') }}</option>
                            @foreach ($ticket_types as $ticket_type)
                                <option value="{{ $ticket_type->id }}" @if ($filters['type'] == $ticket_type->id)selected="selected" @endif>{{ $ticket_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <hr/>

            <div class="table-responsive">
                <a href="{{ route('tickets_add') }}" class="btn pull-right add"></a>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ trans('projectsquare::tickets.ticket') }}</th>
                        <th style="text-align:center">{{ trans('projectsquare::tickets.priority') }}</th>
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
                            <td style="border-left: 10px solid {{ $ticket->project->color }}"></td>
                            <td class="entity_title"><a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">{{ $ticket->title }}</a></td>
                            <td align="center">@if (isset($ticket->states[0]))<span class="priority priority-{{ $ticket->states[0]->priority }}" title="{{ trans('projectsquare::generic.priority-' . $ticket->states[0]->priority) }}"></span>@endif</td>
                            <td>@if (isset($ticket->type)){{ $ticket->type->name }}@endif</td>
                            <td>
                                @if (isset($ticket->states[0]) && isset($ticket->states[0]->author_user))
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $ticket->states[0]->author_user->id,
                                        'name' => $ticket->states[0]->author_user->complete_name
                                    ])
                                @endif
                            </td>
                            <td>
                                @if (isset($ticket->states[0]) && isset($ticket->states[0]->allocated_user))
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $ticket->states[0]->allocated_user->id,
                                        'name' => $ticket->states[0]->allocated_user->complete_name
                                    ])
                                @endif
                            </td>
                            <td>@if (isset($ticket->states[0]) && isset($ticket->states[0]->status))<span class="status status-{{ $ticket->states[0]->status->id }}">{{ $ticket->states[0]->status->name }}</span>@endif</td>
                            <td>@if (isset($ticket->states[0]) && isset($ticket->states[0]->estimated_time_days) && $ticket->states[0]->estimated_time_days > 0){{ $ticket->states[0]->estimated_time_days }} {{ trans('projectsquare::generic.days_abbr') }}@endif @if (isset($ticket->states[0]) && isset($ticket->states[0]->estimated_time_hours) && $ticket->states[0]->estimated_time_hours > 0){{ $ticket->states[0]->estimated_time_hours }} {{ trans('projectsquare::generic.hours_abbr') }}@endif</td>
                            <td>@if (isset($ticket->states[0]) && isset($ticket->states[0]->spent_time_days) && $ticket->states[0]->spent_time_days > 0){{ $ticket->states[0]->spent_time_days }} {{ trans('projectsquare::generic.days_abbr') }}@endif @if (isset($ticket->states[0]) && isset($ticket->states[0]->spent_time_hours) && $ticket->states[0]->spent_time_hours > 0){{ $ticket->states[0]->spent_time_hours }} {{ trans('projectsquare::generic.hours_abbr') }}@endif</td>
                            <td align="right">
                                <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary see-more"></a>
                                <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn cancel btn-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                {!! $tickets->appends([
                    'filter_allocated_user' => $filters['allocated_user'],
                    'filter_status' => $filters['status'],
                    'filter_type' => $filters['type'],
                ])->links() !!}
            </div>
        </div>
    </div>

@endsection