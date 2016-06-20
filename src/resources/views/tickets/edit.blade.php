@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('tickets_index') }}">{{ trans('projectsquare::tickets.tickets_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::tickets.edit_ticket') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::tickets.edit_ticket') }}</h1>
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
    @include('projectsquare::tickets.ticket_infos')

    <hr/>

    <h3>{{ trans('projectsquare::tickets.ticket_state') }}</h3>
    @include('projectsquare::tickets.ticket_state')

    <hr/>

    <h3>{{ trans('projectsquare::tickets.files') }} <span class="badge badge-primary" style="margin-left: 1rem; margin-bottom: 2px;">{{ count($ticket->files) }}</span></h3>
    @include('projectsquare::tickets.ticket_files')

    <hr/>

    <h3>{{ trans('projectsquare::tickets.ticket_history') }} <span class="badge badge-primary" style="margin-left: 1rem; margin-bottom: 2px;">{{ count($ticket->states) }}</span></h3>
    @include('projectsquare::tickets.ticket_history')

    <hr/>

    <h3>{{ trans('projectsquare::tickets.delete_ticket') }}</h3>

    <a href="{{ route('tickets_delete', ['id' => $ticket->id]) }}" class="btn btn-danger btn-delete"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>

    <br/><br/>
@endsection