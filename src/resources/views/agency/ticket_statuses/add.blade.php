@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('ticket_statuses_index') }}">{{ trans('projectsquare::ticket_statuses.ticket_statuses_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::ticket_statuses.add_ticket_status') }}</li>
    </ol>
    <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::ticket_statuses.add_ticket_status') }}</h1>
        </div>

        @include('projectsquare::agency.ticket_statuses.form', [
            'form_action' => route('ticket_statuses_store'),
        ])
    </div>
@endsection