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
                    <th>{{ trans('projectsquare::users.name') }}</th>
                    <th>{{ trans('projectsquare::users.avatar') }}</th>
                    <th>{{ trans('projectsquare::users.email') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->complete_name }}</td>
                             <td>
                                @include('projectsquare::includes.avatar', [
                                    'id' => $user->id,
                                    'name' => $user->complete_name
                                ])
                            </td>
                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td align="right">
                                <a href="{{ route('users_edit', ['id' => $user->id]) }}" class="btn see-more"></a>
                                <a href="{{ route('users_delete', ['id' => $user->id]) }}" class="btn cancel btn-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $users->render() !!}
            </div>
        </div>
    </div>
@endsection