@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('tickets_index') }}">{{ trans('gateway::tickets.tickets_list') }}</a></li>
        <li class="active">{{ trans('gateway::tickets.edit_ticket') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('gateway::tickets.edit_ticket') }}</h1>
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

    <h3>{{ trans('gateway::tickets.ticket_data') }}</h3>
    @include('gateway::tickets.ticket_infos')

    <hr/>

    <h3>{{ trans('gateway::tickets.ticket_state') }}</h3>
    @include('gateway::tickets.ticket_state')

    <hr/>

    <h3>Pièces jointes</h3>
    @include('gateway::tickets.ticket_files')

    <hr/>

    <h3>{{ trans('gateway::tickets.ticket_history') }} <span class="badge badge-primary" style="margin-left: 1rem; margin-bottom: 2px;">{{ count($ticket->states) }}</span></h3>
    @include('gateway::tickets.ticket_history')
@endsection