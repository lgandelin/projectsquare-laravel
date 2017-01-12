@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::ticket_statuses.add_ticket_status') }}</h1>
            </div>

            @include('projectsquare::agency.ticket_statuses.form', [
                'form_action' => route('ticket_statuses_store'),
            ])
        </div>
    </div>
@endsection