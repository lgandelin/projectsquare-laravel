@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::roles.roles_list') }}</li>
    </ol>-->
    <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::roles.roles_list') }}</h1>
        </div>

         <a href="{{ route('roles_add') }}" class="btn pull-right add"></a>
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

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('projectsquare::roles.role') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td align="right">
                            <a href="{{ route('roles_edit', ['id' => $role->id]) }}" class="btn see-more"></a>
                            <a href="{{ route('roles_delete', ['id' => $role->id]) }}" class="btn cancel"></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {!! $roles->render() !!}
        </div>
    </div>
@endsection