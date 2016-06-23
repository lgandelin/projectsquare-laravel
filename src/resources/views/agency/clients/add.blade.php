@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('clients_index') }}">{{ trans('projectsquare::clients.clients_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::clients.add_client') }}</li>
    </ol>-->
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