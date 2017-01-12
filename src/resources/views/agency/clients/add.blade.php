@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::clients.add_client') }}</h1>
            </div>

            @include('projectsquare::agency.clients.form', [
                'form_action' => route('clients_store'),
            ])
        </div>
    </div>
@endsection