@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::clients.add_user') }}</h1>
            </div>

            @include('projectsquare::administration.clients.users.form', [
                'form_action' => route('clients_store_user'),
                'password_field' => true,
                'client_id' => $client->id
            ])
        </div>
    </div>
@endsection