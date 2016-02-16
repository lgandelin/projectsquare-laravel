@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li class="active">Tableau de bord</li>
    </ol>

    <div class="page-header">
        <h1>Tableau de bord</h1>
    </div>

    <div class="dashboard-content">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="block">
                    <h3>Derniers tickets</h3>
                    <div class="block-content table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('gateway::tickets.ticket') }}</th>
                                <th>{{ trans('gateway::tickets.client') }} / {{ trans('gateway::tickets.project') }}</th>
                                <th>{{ trans('gateway::tickets.type') }}</th>
                                <th>{{ trans('gateway::tickets.status') }}</th>
                                <th>{{ trans('gateway::tickets.priority') }}</th>
                                <th>{{ trans('gateway::generic.action') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td width="40%">{{ $ticket->title }}</td>
                                        <td><a href="{{ route('project_index', ['id' => $ticket->project->id]) }}"><span class="label label-primary">{{ $ticket->project->client->name }}</span> {{ $ticket->project->name }}</a></td>
                                        <td><span class="badge">@if (isset($ticket->type)){{ $ticket->type->name }}@endif</span></td>
                                        <td>@if (isset($ticket->states[0]))<span class="status status-{{ $ticket->states[0]->status->id }}">{{ $ticket->states[0]->status->name }}</span>@endif</td>
                                        <td>@if (isset($ticket->states[0]))<span class="badge priority-{{ $ticket->states[0]->priority }}">{{ $ticket->states[0]->priority }}</span>@endif</td>
                                        <td>
                                            <a href="{{ route('tickets_edit', ['id' => $ticket->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('gateway::tickets.see_ticket') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('tickets_add') }}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('gateway::tickets.add_ticket') }}</a>
                        <a href="{{ route('tickets_index') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('gateway::tickets.see_tickets') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="block">
                    <h3>Derniers messages</h3>
                    <div class="block-content table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                @foreach ($conversations as $conversation)
                                    <tr>
                                        <td>
                                            <a href="{{ route('project_index', ['id' => $conversation->project->id]) }}"><span class="label label-primary">{{ $conversation->project->client->name }}</span> {{ $conversation->project->name }}</a><br>
                                            <strong>{{ $conversation->title }}</strong> par {{ $conversation->messages[0]->user->complete_name }}<br><br>

                                            <span class="badge">{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</span> <br/>
                                            <p>{{ $conversation->messages[0]->content }}</p>
                                            <a href="{{ route('project_messages', ['id' => $conversation->project->id]) }}" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-share-alt"></span> voir</a>
                                            <a href="{{ route('messages_reply', ['id' => $conversation->messages[0]->id]) }}" class="btn btn-success pull-right" style="margin-right: 1rem;"><span class="glyphicon glyphicon-envelope"></span> {{ trans('gateway::messages.reply_message') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('messages_add') }}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('gateway::messages.add_conversation') }}</a>
                        <a href="{{ route('messages_index') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('gateway::messages.see_messages') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="block">
                    <h3>Planning</h3>
                    <div class="block-content"></div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="block">
                    <h3>Alertes monitoring</h3>
                    <div class="block-content table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Variables</th>
                                <th>Temps de chargement</th>
                                <th>{{ trans('gateway::generic.action') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach ($alerts as $alert)
                                    <tr>
                                        <td>{{ $alert->id }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($alert->created_at)) }}</td>
                                        <td><span class="badge">{{ $alert->type }}</span></td>
                                        <td><a href="{{ route('project_index', ['id' => $alert->project->id]) }}"><span class="label label-primary">{{ $alert->project->client->name }}</span> {{ $alert->project->name }}</a></td>
                                        <td>{{ number_format($alert->variables->loading_time, 2) }}s</td>
                                        <td>
                                            <a href="{{ route('project_monitoring', ['id' => $alert->project->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span> voir le projet</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('monitoring_index') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('gateway::monitoring.see_alerts') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection