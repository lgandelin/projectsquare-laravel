@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::users.users_list') }}</h1>
                @include('projectsquare::includes.tooltip', [
                 'text' => trans('projectsquare::tooltips.contributors_list')
                ])
            </div>
              <a href="{{ route('users_add') }}" class="btn pull-right add"></a>

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
                    <th>{{ trans('projectsquare::users.name') }}<a href="{{ route('users_index', ['sc' => 'first_name', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-alpha-{{ $sort_order }}"></i></a></th>
                    <th>{{ trans('projectsquare::users.avatar') }}</th>
                    <th>{{ trans('projectsquare::users.email') }}</th>
                    <th>{{ trans('projectsquare::users.profile') }}<a href="{{ route('users_index', ['sc' => 'role_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('users_edit', ['id' => $user->id]) }}">
                                    {{ $user->complete_name }}
                                </a>
                            </td>
                             <td>
                                 <a href="{{ route('users_edit', ['id' => $user->id]) }}">
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $user->id,
                                        'name' => $user->complete_name
                                    ])
                                 </a>
                            </td>
                            <td>
                                <a href="{{ route('users_edit', ['id' => $user->id]) }}">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('users_edit', ['id' => $user->id]) }}">
                                    @if ($user->role){{ $user->role->name }}@endif
                                </a>
                            </td>
                            <td class="action" align="right">
                                <a href="{{ route('users_edit', ['id' => $user->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                                <a href="{{ route('users_delete', ['id' => $user->id]) }}">
                                    <i class="btn cancel btn-delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                @include('projectsquare::administration.includes.items_per_page')
                {{ $users->appends(['it' => $items_per_page, 'sc' => $sort_column, 'so' => $sort_order])->links() }}
            </div>
        </div>
    </div>
@endsection