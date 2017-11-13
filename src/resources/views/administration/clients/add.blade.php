@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::clients.add_client') }}</h1>
                  <a href="{{ route('clients_index') }}" class="btn back"></a>
            </div>

            @include('projectsquare::administration.clients.form', [
                'form_action' => route('clients_store'),
            ])
        </div>
    </div>
@endsection