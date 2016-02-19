@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Messages</li>
    </ol>

    <div class="page-header">
        <h1>Messages</h1>

        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Projet</th>
                    <th>Auteur</th>
                    <th>Titre</th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach ($conversations as $conversation)
                <tr>
                    <td>{{ $conversation->id }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</td>
                    <td><span class="label label-primary">{{ $conversation->project->client->name }}</span> {{ $conversation->project->name }}</td>
                    <td>{{ $conversation->messages[0]->user->complete_name }}</td>
                    <td>{{ $conversation->title }}</td>
                    <td>
                        <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('gateway::generic.see') }}</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection