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
                <div class="block last-messages">
                    <h3>Derniers messages</h3>
                    <div class="wrapper">
                        <div class="block-content table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    @foreach ($conversations as $conversation)
                                        <tr class="conversation">
                                            <td>
                                                <span class="badge pull-right count"><span class="number">{{ count($conversation->messages) }}</span> @if (count($conversation->messages) > 1)messages @else message @endif</span>
                                                <a href="{{ route('project_index', ['id' => $conversation->project->id]) }}"><span class="label label-primary">{{ $conversation->project->client->name }}</span> {{ $conversation->project->name }}</a> - <strong>{{ $conversation->title }}</strong><br><br/>

                                                <div class="message new-message" style="display:none">
                                                    <textarea class="form-control" placeholder="Votre message"></textarea>
                                                    <button class="btn btn-default pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('gateway::generic.cancel') }}</button>
                                                    <button class="btn btn-success pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('gateway::generic.valid') }}</button>
                                                </div>

                                                <div class="message-inserted"></div>

                                                @foreach ($conversation->messages as $i => $message)
                                                    <div class="message">
                                                        <span class="badge">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span> <span class="glyphicon glyphicon-user"></span> <span class="user_name">{{ $message->user->complete_name }}</span><br/>
                                                        <p class="content">{{ $message->content }}</p>
                                                        <a href="{{ route('project_messages', ['id' => $conversation->id]) }}" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-share-alt"></span> voir</a>
                                                        <button class="btn btn-success pull-right reply-message" data-id="{{ $message->id }}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span> {{ trans('gateway::messages.reply_message') }}</button>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <a href="{{ route('messages_add') }}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('gateway::messages.add_conversation') }}</a>
                    <a href="{{ route('messages_index') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('gateway::messages.see_messages') }}</a>
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

    @include('gateway::dashboard.new-message')
@endsection