@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('ticket_types_index') }}">{{ trans('projectsquare::ticket_types.ticket_types_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::ticket_types.edit_ticket_type') }}</li>
    </ol>-->
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::ticket_types.edit_ticket_type') }}</h1>
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

            @include('projectsquare::agency.ticket_types.form', [
                'form_action' => route('ticket_types_update'),
                'ticket_type_id' => $ticket_type->id,
                'ticket_type_name' => $ticket_type->name
            ])
        </div>
    </div>
@endsection