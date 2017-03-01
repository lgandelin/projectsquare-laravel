@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::tickets.edit_ticket') }}</h1>
                <a href="{{ $back_link }}" class="btn back"></a>
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

            <h3>{{ trans('projectsquare::tickets.ticket_data') }}</h3>
            @include('projectsquare::tools.tickets.ticket_infos')

            <hr/>

            <h3>{{ trans('projectsquare::tickets.ticket_state') }}</h3>
            @include('projectsquare::tools.tickets.ticket_state')

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
                <span> Supprimer </span>
            </a>

            <br/><br/>
        </div>
    </div>
@endsection

@section('scripts')
    @include('projectsquare::includes/project_users_selection')
@endsection