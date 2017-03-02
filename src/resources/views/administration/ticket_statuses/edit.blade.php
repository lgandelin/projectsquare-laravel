@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::ticket_statuses.edit_ticket_status') }}</h1>
                <a href="{{ route('ticket_types_index') }}" class="btn back"></a>
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

            @include('projectsquare::administration.ticket_statuses.form', [
                'form_action' => route('ticket_statuses_update'),
                'ticket_status_id' => $ticket_status->id,
                'ticket_status_name' => $ticket_status->name,
                'ticket_status_include_in_planning' => $ticket_status->include_in_planning
            ])
        </div>
    </div>
@endsection