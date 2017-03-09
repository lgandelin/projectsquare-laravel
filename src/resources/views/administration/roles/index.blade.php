@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::roles.roles_list') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.roles_list')
                  ])
                </h1>
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
                        <th>{{ trans('projectsquare::roles.role') }}</th>
                        <th>{{ trans('projectsquare::generic.action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td class="entity_title"><a href="{{ route('roles_edit', ['id' => $role->id]) }}">{{ $role->name }}</a></td>
                            <td align="right">
                                <a href="{{ route('roles_edit', ['id' => $role->id]) }}" class="btn see-more"></a>
                                <a href="{{ route('roles_delete', ['id' => $role->id]) }}" class="btn cancel btn-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $roles->render() !!}
            </div>
        </div>
    </div>
@endsection