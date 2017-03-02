@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::ticket_types.add_ticket_type') }}</h1>
                <a href="{{ route('ticket_types_index') }}" class="btn back"></a>
            </div>

            @include('projectsquare::administration.ticket_types.form', [
                'form_action' => route('ticket_types_store'),
            ])
        </div>
    </div>
@endsection