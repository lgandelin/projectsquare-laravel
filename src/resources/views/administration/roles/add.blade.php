@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::roles.add_role') }}</h1>
                <a href="{{ route('roles_index') }}" class="btn back"></a>
            </div>

            @include('projectsquare::administration.roles.form', [
                'form_action' => route('roles_store'),
            ])
        </div>
    </div>
@endsection