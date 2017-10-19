@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::ticket_types.edit_ticket_type') }}</h1>
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

            @include('projectsquare::administration.ticket_types.form', [
                'form_action' => route('ticket_types_update'),
                'ticket_type_id' => $ticket_type->id,
                'ticket_type_name' => $ticket_type->name
            ])
        </div>
    </div>
@endsection