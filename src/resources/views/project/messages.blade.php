@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'messages'])
    <div class="content-page">
        <div class="templates messages-list-template">
            <h1 class="page-header">{{ trans('projectsquare::project.messages') }}
                 @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.messages')
                  ])
            </h1>

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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
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
                            <td class="project-border" style="border-left: 10px solid {{ $conversation->project->color }}"></td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    {{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    @if (count($conversation->messages) > 0)
                                        @include('projectsquare::includes.avatar', [
                                            'id' => $conversation->messages[sizeof($conversation->messages) - 1]->user->id,
                                            'name' => $conversation->messages[sizeof($conversation->messages) - 1]->user->complete_name
                                        ])
                                    @else
                                        <img class="default-avatar avatar" src="{{ asset('img/default-avatar.jpg') }}" />
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    {{ $conversation->title }}
                                </a>
                            </td>
                            <td width="50%">
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    @if (isset($conversation->messages[count($conversation->messages) - 1])){{ str_limit($conversation->messages[count($conversation->messages) - 1]->content, 200) }}@endif
                                </a>
                            </td>
                            <td width="5%" class="action" align="right">
                                <a href="{{ route('conversations_view', ['id' => $conversation->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if ($is_client)
                <button class="btn valid create-conversation"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::messages.add_conversation') }}</button>
            @endif
        </div>
    </div>
    @include('projectsquare::dashboard.new-message')
    @include('projectsquare::dashboard.create-conversation-modal')
    <script src="{{ asset('js/messages.js') }}"></script>
@endsection