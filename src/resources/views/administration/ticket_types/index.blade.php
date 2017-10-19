@extends('projectsquare::default')

@section('content')
     <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ __('projectsquare::ticket_types.ticket_types_list') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => __('projectsquare::tooltips.tickets_types_list')
                  ])
                </h1>
            </div>

            <a href="{{ route('ticket_types_add') }}" class="btn pull-right add"></a>

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
                        <th>{{ __('projectsquare::ticket_types.ticket_type') }}</th>
                        <th>{{ __('projectsquare::generic.action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ticket_types as $ticket_type)
                        <tr>
                            <td>
                                <a href="{{ route('ticket_types_edit', ['id' => $ticket_type->id]) }}">
                                    {{ $ticket_type->name }}
                                </a>
                            </td>
                            <td width="5%" class="action" align="right">
                                <a href="{{ route('ticket_types_edit', ['id' => $ticket_type->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                                <a href="{{ route('ticket_types_delete', ['id' => $ticket_type->id]) }}">
                                    <i class="btn cancel btn-delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $ticket_types->render() !!}
            </div>
        </div>
    </div>
@endsection