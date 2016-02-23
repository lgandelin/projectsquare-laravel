@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar', ['active' => 'messages
    '])

    <div class="settings-template">
        <h1 class="page-header">{{ trans('gateway::project.messages') }}</h1>

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

        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Auteur</th>
                <th>Titre</th>
                <th>Dernier message</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($conversations as $conversation)
                    <tr>
                        <td>{{ $conversation->id }}</td>
                        <td>{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</td>
                        <td>@if (isset($conversation->messages[0])){{ $conversation->messages[0]->user->complete_name }}@endif</td>
                        <td>{{ $conversation->title }}</td>
                        <td>@if (isset($conversation->messages[count($conversation->messages) - 1])){{ $conversation->messages[count($conversation->messages) - 1]->content }}@endif</td>
                        <td>
                            <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('gateway::generic.see') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection