@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::clients.clients_list') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => __('projectsquare::tooltips.clients_list')
                  ])
                </h1>
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
                    <th>{{ __('projectsquare::clients.client') }}<a href="{{ route('clients_index', ['sc' => 'name', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-alpha-{{ $sort_order }}"></i></a></th>
                    <th>{{ __('projectsquare::clients.creation_date') }}<a href="{{ route('clients_index', ['sc' => 'created_at', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-{{ $sort_order }}"></i></a></th>
                    <th align="right">{{ __('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>
                                <a href="{{ route('clients_edit', ['id' => $client->id]) }}">
                                    {{ $client->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('clients_edit', ['id' => $client->id]) }}">
                                    @if ($client->created_at)
                                        {{ DateTime::createFromFormat('Y-m-d H:i:s', $client->created_at)->format('d/m/Y') }}
                                    @endif
                                </a>
                            </td>
                            <td width="5%" class="action" align="right">
                                <a href="{{ route('clients_edit', ['id' => $client->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                                <a href="{{ route('clients_delete', ['id' => $client->id]) }}">
                                    <i class="btn cancel btn-delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                @include('projectsquare::administration.includes.items_per_page')
                {{ $clients->appends(['it' => $items_per_page, 'sc' => $sort_column, 'so' => $sort_order])->links() }}
            </div>
        </div>
    </div>
@endsection