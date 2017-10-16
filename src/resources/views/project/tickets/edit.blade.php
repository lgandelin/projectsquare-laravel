@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'tickets'])

    <div class="templates project-tickets-template">
        @include('projectsquare::project.tickets.middle-column', ['currentTicketID' => $ticket->id])

        <div class="content-page">
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

            @include('projectsquare::project.tickets.infos')

            <hr/>

            <h3>{{ trans('projectsquare::tickets.ticket_state') }}</h3>
            @include('projectsquare::project.tickets.state')

            <hr/>

            <h3>{{ trans('projectsquare::tickets.files') }} <span class="badge badge-primary" style="margin-left: 1rem; margin-bottom: 2px;">{{ count($files) }}</span></h3>
            @include('projectsquare::tools.tickets.ticket_files')

            <hr/>

            <h3>{{ trans('projectsquare::tickets.ticket_history') }} <span class="badge badge-primary" style="margin-left: 1rem; margin-bottom: 2px;">{{ count($ticket->states) }}</span></h3>
            @include('projectsquare::tools.tickets.ticket_history')

            <hr/>

            <h3>{{ trans('projectsquare::tickets.delete_ticket') }}</h3>

            <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn delete btn-delete">
                <i class="glyphicon glyphicon-remove picto-delete"></i>
                <span>{{ trans('projectsquare::generic.delete') }}</span>
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection')
@endsection
