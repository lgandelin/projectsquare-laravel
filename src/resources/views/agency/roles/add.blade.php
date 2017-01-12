@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::roles.add_role') }}</h1>
            </div>

            @include('projectsquare::agency.roles.form', [
                'form_action' => route('roles_store'),
            ])
        </div>
    </div>
@endsection