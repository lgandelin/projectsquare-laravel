@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'index'])

    <div class="project-template">
        <h1 class="page-header">{{ __('projectsquare::project.summary') }}</h1>

        <h3>{{ __('projectsquare::project.last_tickets') }}</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('projectsquare::tickets.ticket') }}</th>
                    <th>{{ __('projectsquare::tickets.type') }}</th>
                    <th>{{ __('projectsquare::tickets.author_user') }}</th>
                    <th>{{ __('projectsquare::tickets.allocated_user') }}</th>
                    <th>{{ __('projectsquare::tickets.status') }}</th>
                    <th>{{ __('projectsquare::tickets.priority') }}</th>
                    <th>{{ __('projectsquare::generic.action') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td width="40%">@if (isset($ticket->title)){{ $ticket->title }}@endif</td>
                        <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                        <td>
                            @if (isset($ticket->last_state) && isset($ticket->last_state->author_user))
                                @include('projectsquare::includes.avatar', [
                                    'id' => $ticket->last_state->author_user->id,
                                    'name' => $ticket->last_state->author_user->complete_name
                                ])
                            @endif
                        </td>
                        <td>
                            @if (isset($ticket->last_state) && isset($ticket->last_state->allocated_user))
                                @include('projectsquare::includes.avatar', [
                                    'id' => $ticket->last_state->allocated_user->id,
                                    'name' => $ticket->last_state->allocated_user->complete_name
                                ])
                            @endif
                        </td>
                        <td>@if (isset($ticket->last_state) && isset($ticket->last_state->status))<span class="status status-{{ $ticket->last_state->status->id }}">{{ $ticket->last_state->status->name }}</span>@endif</td>
                        <td>@if (isset($ticket->last_state))<span class="badge priority-{{ $ticket->last_state->priority }}">{{ $ticket->last_state->priority }}</span>@endif</td>
                        <td>
                            <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span>{{ __('projectsquare::tickets.see_ticket') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('tickets_add') }}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> {{ __('projectsquare::tickets.add_ticket') }}</a>
        <a href="{{ route('project_tickets', ['id' => $project->id]) }}" class="btn pull-right btn-default"><span class="glyphicon glyphicon-list-alt"></span> {{ __('projectsquare::tickets.see_tickets') }}</a>
    </div>

@endsection