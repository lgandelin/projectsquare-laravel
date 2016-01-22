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

    @include('gateway::tickets.form', [
        'form_action' => route('tickets_update'),
        'ticket_id' => $ticket->id,
        'ticket_title' => $ticket->title,
        'ticket_description' => $ticket->description,
        'ticket_due_date' => $ticket->due_date
    ])
@endsection