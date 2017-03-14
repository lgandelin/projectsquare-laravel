@extends('projectsquare::default')

@section('content')
     <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::ticket_types.ticket_types_list') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.tickets_types_list')
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
                        <th>{{ trans('projectsquare::ticket_types.ticket_type') }}</th>
                        <th>{{ trans('projectsquare::generic.action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ticket_types as $ticket_type)
                        <tr>
                            <td class="entity_title"><a href="{{ route('ticket_types_edit', ['id' => $ticket_type->id]) }}">{{ $ticket_type->name }}</a></td>
                            <td align="right">
                                <a href="{{ route('ticket_types_edit', ['id' => $ticket_type->id]) }}" class="btn see-more"></a>
                                <a href="{{ route('ticket_types_delete', ['id' => $ticket_type->id]) }}" class="btn cancel btn-delete"></a>
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