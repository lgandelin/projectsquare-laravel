@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('ticket_types_index') }}">{{ trans('projectsquare::ticket_types.ticket_types_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::ticket_types.add_ticket_type') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::ticket_types.add_ticket_type') }}</h1>
    </div>

    @include('projectsquare::agency.ticket_types.form', [
        'form_action' => route('ticket_types_store'),
    ])
@endsection