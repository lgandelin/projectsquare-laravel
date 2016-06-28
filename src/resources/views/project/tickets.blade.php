@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tickets'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.tickets') }}</h1>
             <a href="{{ route('tickets_add') }}" class="btn pull-right add"></a>
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

                            <td>@if (isset($ticket->states[0]))<span class="priority priority-{{ $ticket->states[0]->priority }}"></span>@endif</td>
                            <td align="right">
                                <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary see-more"></a>
                                <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn cancel btn-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection