@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li class="active">Tableau de bord</li>
    </ol>

    <div class="page-header">
        <h1>Tableau de bord</h1>
    </div>

    <div class="row dashboard-content">
        <div class="col-md-6">
            <div class="block">
                <h3>Derniers tickets</h3>
                <div class="block-content">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('gateway::tickets.ticket') }}</th>
                            <th>{{ trans('gateway::tickets.client') }} / {{ trans('gateway::tickets.project') }}</th>
                            <th>{{ trans('gateway::tickets.type') }}</th>
                            <th>{{ trans('gateway::generic.action') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->title }}</td>
                                    <td><span class="label label-primary">{{ $ticket->project->client->name }}</span> {{ $ticket->project->name }}</td>
                                    <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                                    <td>
                                        <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary">Voir le ticket</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <a href="{{ route('tickets_add') }}" class="btn btn-success">{{ trans('gateway::tickets.add_ticket') }}</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="block">
                <h3>Alertes monitoring</h3>
                <div class="block-content"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="block">
                <h3>Calendrier</h3>
                <div class="block-content"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="block">
                <h3>Derniers messages</h3>
                <div class="block-content"></div>
            </div>
        </div>
    </div>
@endsection