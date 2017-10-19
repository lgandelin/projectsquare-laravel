@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates tickets-template">
            <div class="page-header">
                <h1>{{ __('projectsquare::tickets.tickets_list') }}
                     @include('projectsquare::includes.tooltip', [
                        'text' => __('projectsquare::tooltips.tickets')
                  ])
                </h1>
            </div>

            <form method="get">
                <div class="row">
                    <h2> {{ __('projectsquare::tasks.filters.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_project" id="filter_project">
                            <option value="na">{{ __('projectsquare::tickets.filters.by_project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>@if (isset($project->client)){{ $project->client->name }} -@endif {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_allocated_user" id="filter_allocated_user">
                            <option value="na">{{ __('projectsquare::tickets.filters.by_allocated_user') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($filters['allocated_user'] == $user->id)selected="selected" @endif>{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_status" id="filter_status">
                            <option value="na">{{ __('projectsquare::tickets.filters.by_status') }}</option>
                            @foreach ($ticket_statuses as $ticket_status)
                                <option value="{{ $ticket_status->id }}" @if ($filters['status'] == $ticket_status->id)selected="selected" @endif>{{ $ticket_status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_type" id="filter_type">
                            <option value="na">{{ __('projectsquare::tickets.filters.by_type') }}</option>
                            @foreach ($ticket_types as $ticket_type)
                                <option value="{{ $ticket_type->id }}" @if ($filters['type'] == $ticket_type->id)selected="selected" @endif>{{ $ticket_type->name }}</option>
                            @endforeach
                        </select>
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

            <a href="{{ route('tickets_add') }}" class="create-button">
                {{ __('projectsquare::tickets.add_ticket') }}

                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('projectsquare::tickets.ticket') }}<a href="{{ route('tickets_index', ['sc' => 'title', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-alpha-{{ $sort_order }}"></i></a></th>
                            <th style="text-align: center;">{{ __('projectsquare::tickets.priority') }}<a href="{{ route('tickets_index', ['sc' => 'priority', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-amount-@if ($sort_order == 'asc'){{ 'desc' }}@else{{ 'asc' }}@endif"></i></a></th>
                            <th>{{ __('projectsquare::tickets.client') }}<a href="{{ route('tickets_index', ['sc' => 'client', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-alpha-{{ $sort_order }}"></i></a></th>
                            <th>{{ __('projectsquare::tickets.type') }}<a href="{{ route('tickets_index', ['sc' => 'type_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                            <th>{{ __('projectsquare::tickets.author_user') }}<a href="#" class="sort-icon"><i class="fa fa-sort" style="visibility: hidden"></i></a></th>
                            <th>{{ __('projectsquare::tickets.allocated_user') }}<a href="{{ route('tickets_index', ['sc' => 'allocated_user_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                            <th>{{ __('projectsquare::tickets.status') }}<a href="{{ route('tickets_index', ['sc' => 'status_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                            <th>{{ __('projectsquare::tickets.estimated_time') }}<a href="#" class="sort-icon"><i class="fa fa-sort" style="visibility: hidden"></i></a></th>
                            <th>{{ __('projectsquare::tickets.spent_time') }}<a href="#" class="sort-icon"><i class="fa fa-sort" style="visibility: hidden"></i></a></th>
                            <th>{{ __('projectsquare::generic.action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td class="project-border" style="border-left: 10px solid {{ $ticket->project->color }}"></td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        {{ $ticket->title }}
                                    </a>
                                </td>
                                <td align="center">
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->last_state))<span class="priority priority-{{ $ticket->last_state->priority }}" title="{{ __('projectsquare::generic.priority-' . $ticket->last_state->priority) }}"></span>@endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->project) && isset($ticket->project->client)){{ $ticket->project->client->name }}@endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->type)){{ $ticket->type->name }}@endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->states[count($ticket->states) - 1]))
                                            @include('projectsquare::includes.avatar', [
                                                'id' => $ticket->states[count($ticket->states) - 1]->author_user->id,
                                                'name' => $ticket->states[count($ticket->states) - 1]->author_user->complete_name
                                            ])
                                        @else
                                            <img class="default-avatar avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->last_state) && $ticket->last_state->allocated_user)
                                            @include('projectsquare::includes.avatar', [
                                                'id' => $ticket->last_state->allocated_user->id,
                                                'name' => $ticket->last_state->allocated_user->complete_name
                                            ])
                                        @else
                                            <img class="default-avatar avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->last_state) && $ticket->last_state->status)<span class="status status-{{ $ticket->last_state->status->id }}">{{ $ticket->last_state->status->name}}</span>@endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->last_state) && $ticket->last_state->estimated_time_days > 0){{ $ticket->last_state->estimated_time_days }}{{ __('projectsquare::generic.days_abbr') }}@endif @if (isset($ticket->last_state) && $ticket->last_state->estimated_time_hours > 0){{ $ticket->last_state->estimated_time_hours }} {{ __('projectsquare::generic.hours_abbr') }}@endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        @if (isset($ticket->last_state) && $ticket->last_state->spent_time_days > 0){{ $ticket->last_state->spent_time_days }} {{ __('projectsquare::generic.days_abbr') }}@endif @if (isset($ticket->last_state) && $ticket->last_state->spent_time_hours > 0){{ $ticket->last_state->spent_time_hours }} {{ __('projectsquare::generic.hours_abbr') }}@endif
                                    </a>
                                </td>
                                <td class="action" align="right">
                                    <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}">
                                        <i class="btn see-more"></i>
                                    </a>
                                    <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}"><i class="btn cancel btn-delete"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                @include('projectsquare::administration.includes.items_per_page')
                {!! $tickets->appends([
                    'filter_project' => $filters['project'],
                    'filter_allocated_user' => $filters['allocated_user'],
                    'filter_status' => $filters['status'],
                    'filter_type' => $filters['type'],
                    'it' => $items_per_page,
                    'sc' => $sort_column,
                    'so' => $sort_order
                ])->links() !!}
            </div>
        </div>
    </div>
@endsection