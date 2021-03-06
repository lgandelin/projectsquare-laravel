@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::ticket_statuses.ticket_statuses_list') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => __('projectsquare::tooltips.tickets_statuses_list')
                  ])
                </h1>
            </div>

             <a href="{{ route('ticket_statuses_add') }}" class="btn pull-right add"></a>

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
                        <th>{{ __('projectsquare::ticket_statuses.ticket_status') }}</th>
                        <th>{{ __('projectsquare::generic.action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ticket_statuses as $ticket_status)
                        <tr>
                            <td>
                                <a href="{{ route('ticket_statuses_edit', ['id' => $ticket_status->id]) }}">
                                    {{ $ticket_status->name }}
                                </a>
                            </td>
                            <td width="5%" class="action" align="right">
                                <a href="{{ route('ticket_statuses_edit', ['id' => $ticket_status->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                                <a href="{{ route('ticket_statuses_delete', ['id' => $ticket_status->id]) }}">
                                    <i class="btn cancel btn-delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $ticket_statuses->render() !!}
            </div>
        </div>
    </div>
@endsection