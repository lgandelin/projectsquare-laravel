@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::clients.edit_client') }}</h1>
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

            @include('projectsquare::administration.clients.form', [
                'form_action' => route('clients_update'),
                'client_id' => $client->id,
                'client_name' => $client->name,
                'client_address' => $client->address
            ])

            <br>
            <br>

            <h2>
                {{ trans('projectsquare::clients.client_accounts') }}
                @include('projectsquare::includes.tooltip', [
                    'text' => trans('projectsquare::tooltips.client_accounts')
                ])
            </h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{ trans('projectsquare::users.name') }}</th>
                        <th>{{ trans('projectsquare::users.email') }}</th>
                        <th>{{ trans('projectsquare::users.role') }}</th>
                        <th>{{ trans('projectsquare::generic.action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->complete_name }}</td>
                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td>{{ $user->client_role }}</td>
                            <td align="right">
                                <a href="{{ route('clients_edit_user', ['client_id' => $client->id, 'user_id' => $user->id]) }}" class="btn button"><span class="glyphicon glyphicon-pencil"></span> <span class="value">{{ trans('projectsquare::generic.edit') }}</span></a>
                                <a href="{{ route('clients_delete_user', ['client_id' => $client->id, 'user_id' => $user->id]) }}" class="btn btn-delete delete"><span class="glyphicon glyphicon-remove"></span> <span class="value">{{ trans('projectsquare::generic.delete') }}</span></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('clients_add_user', ['id' => $client->id]) }}" class="btn valid users"><i class="glyphicon glyphicon-plus"></i> {{ trans('projectsquare::clients.add_user') }}</a>
        </div>
    </div>
@endsection