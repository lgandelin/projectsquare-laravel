@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'messages'])
    <div class="content-page">
        <div class="templates settings-template">
            <h1 class="page-header">{{ trans('projectsquare::project.messages') }}</h1>

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
                    <th>{{ trans('projectsquare::messages.date') }}</th>
                    <th>{{ trans('projectsquare::messages.author') }}</th>
                    <th>{{ trans('projectsquare::messages.title') }}</th>
                    <th>{{ trans('projectsquare::messages.last_message') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
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
                            <td align="right">
                                <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn see-more"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($is_client)
                <button class="btn btn-sm btn-success create-conversation"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::messages.add_conversation') }}</button>
            @endif
        </div>
    </div>
    @include('projectsquare::dashboard.new-message')
    @include('projectsquare::dashboard.create-conversation-modal')
@endsection