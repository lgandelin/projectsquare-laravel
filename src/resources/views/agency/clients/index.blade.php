@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::clients.clients_list') }}</li>
    </ol>-->
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::clients.clients_list') }}</h1>
            </div>
             <a href="{{ route('clients_add') }}" class="btn pull-right add"></a>

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
                    <th>{{ trans('projectsquare::clients.client') }}</th>
                    <th align="right">{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td align="right">
                                <a href="{{ route('clients_edit', ['id' => $client->id]) }}" class="btn see-more"></a>
                                <a href="{{ route('clients_delete', ['id' => $client->id]) }}" class="btn cancel btn-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $clients->render() !!}
            </div>
        </div>
    </div>
@endsection