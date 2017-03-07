@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::roles.edit_role') }}</h1>
                <a href="{{ route('roles_index') }}" class="btn back"></a>
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

            @include('projectsquare::administration.roles.form', [
                'form_action' => route('roles_update'),
                'role_id' => $role->id,
                'role_name' => $role->name
            ])
        </div>
    </div>
@endsection