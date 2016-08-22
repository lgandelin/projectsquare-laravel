@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('clients_index') }}">{{ trans('projectsquare::clients.clients_list') }}</a></li>
        <li><a href="{{ route('clients_edit', ['id' => $client->id]) }}">{{ trans('projectsquare::clients.edit_client') }}</a></li>
        <li class="active">{{ trans('projectsquare::clients.add_user') }}</li>
    </ol>-->
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::clients.add_user') }}</h1>
            </div>

            @include('projectsquare::agency.clients.users.form', [
                'form_action' => route('clients_store_user'),
                'password_field' => true,
                'client_id' => $client->id
            ])
        </div>
    </div>
@endsection