@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('ticket_statuses_index') }}">{{ trans('projectsquare::ticket_statuses.ticket_statuses_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::ticket_statuses.edit_ticket_status') }}</li>
    </ol>
    <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::ticket_statuses.edit_ticket_status') }}</h1>
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

        @include('projectsquare::agency.ticket_statuses.form', [
            'form_action' => route('ticket_statuses_update'),
            'ticket_status_id' => $ticket_status->id,
            'ticket_status_name' => $ticket_status->name
        ])
    </div>
@endsection