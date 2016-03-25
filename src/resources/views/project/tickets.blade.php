@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tickets'])

    <div class="project-template">
        <h1 class="page-header">{{ trans('projectsquare::project.summary') }}</h1>

        <h3>{{ trans('projectsquare::project.tickets') }}</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('projectsquare::tickets.ticket') }}</th>
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
                        <td width="40%">{{ $ticket->title }}</td>
                        <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                        <td>@if (isset($ticket->states[0])){{ $ticket->states[0]->author_user->complete_name }}@endif</td>
                        <td>@if (isset($ticket->states[0])){{ $ticket->states[0]->allocated_user->complete_name }}@endif</td>
                        <td>@if (isset($ticket->states[0]))<span class="status status-{{ $ticket->states[0]->status->id }}">{{ $ticket->states[0]->status->name }}</span>@endif</td>
                        <td>@if (isset($ticket->states[0]))<span class="badge priority-{{ $ticket->states[0]->priority }}">{{ $ticket->states[0]->priority }}</span>@endif</td>
                        <td>
                            <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                            <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('tickets_add') }}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::tickets.add_ticket') }}</a>
    </div>

@endsection