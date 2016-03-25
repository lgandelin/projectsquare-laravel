@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li><a href="{{ route('messages_index') }}">Messages</a></li>
        <li class="active">{{ $conversation->title }}</li>
    </ol>

    <div class="message-view-template">
        <div class="page-header">
            <h1>{{ $conversation->title }}</h1>
        </div>

        <div class="col-md-8">
            <div class="header">
                <a href="{{ route('project_index', ['id' => $conversation->project->id]) }}"><span class="label label-primary">{{ $conversation->project->client->name }}</span> {{ $conversation->project->name }}</a>
                <span class="badge pull-right count"><span class="number">{{ count($conversation->messages) }}</span> @if (count($conversation->messages) > 1)messages @else message @endif</span>
            </div>

            <div class="conversation">
                @foreach ($conversation->messages as $i => $message)
                    <tr>
                        <div class="message">
                            <span class="badge">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span> <span class="glyphicon glyphicon-user"></span> <span class="user_name">{{ $message->user->complete_name }}</span><br/>
                            <p class="content" style="margin-top: 1rem; @if ($message->read == false) font-weight: bold;@endif">{{ $message->content }}</p>
                        </div>
                    </tr>
                @endforeach

                <div class="message-inserted"></div>

                <div class="message new-message" style="display:none">
                    <textarea class="form-control" placeholder="Votre message"></textarea>
                    <button class="btn btn-default pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                    <button class="btn btn-success pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('projectsquare::generic.valid') }}</button>
                </div>

                <div class="submit">
                    <a href="{{ route('messages_index') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.back') }}</a>
                    <button class="btn btn-success pull-right reply-message" data-id="{{ $message->id }}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span> {{ trans('projectsquare::messages.reply_message') }}</button>
                </div>
            </div>
        </div>
    </div>

    @include('projectsquare::dashboard.new-message')
@endsection