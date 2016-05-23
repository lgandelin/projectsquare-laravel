<div class="block last-messages">
    <h3>{{ trans('projectsquare::dashboard.last_messages') }}</h3>
    <div class="wrapper">
        <div class="block-content table-responsive">
            <table class="table table-striped">
                <tbody>
                @foreach ($conversations as $conversation)
                    <tr class="conversation">
                        <td>
                            <span class="badge pull-right count"><span class="number">{{ count($conversation->messages) }}</span> @if (count($conversation->messages) > 1){{ trans('projectsquare::dashboard.messages') }} @else {{ trans('projectsquare::dashboard.message') }} @endif</span>
                            <a href="{{ route('project_index', ['id' => $conversation->project->id]) }}"><span class="label" style="background: {{ $conversation->project->color }}">{{ $conversation->project->client->name }}</span> {{ $conversation->project->name }}</a> - <strong>{{ $conversation->title }}</strong><br>

                            <div class="users">
                                <u>{{ trans('projectsquare::dashboard.conversation_participants') }}</u> :
                                @foreach ($conversation->users as $i => $user)
                                    @if ($i > 0),@endif {{ $user->complete_name }}
                                @endforeach
                            </div>

                            @foreach ($conversation->messages as $message)
                                <div class="message">
                                    <span class="badge">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span> <span class="glyphicon glyphicon-user"></span> <span class="user_name">{{ $message->user->complete_name }}</span><br/>
                                    <p class="content">{{ $message->content }}</p>
                                </div>
                            @endforeach

                            <div class="message-inserted"></div>

                            <div class="message new-message" style="display:none">
                                <textarea class="form-control" placeholder="{{ trans('projectsquare::dashboard.your_message') }}" rows="4"></textarea>
                                <button class="btn btn-sm btn-default pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                                <button class="btn btn-sm btn-success pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('projectsquare::generic.valid') }}</button>
                            </div>

                            <div class="submit">
                                <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-sm btn-primary pull-right"><span class="glyphicon glyphicon-share-alt"></span> {{ trans('projectsquare::messages.see_message') }}</a>
                                <button class="btn btn-sm btn-success pull-right reply-message" data-id="{{ $message->id }}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span> {{ trans('projectsquare::messages.reply_message') }}</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if ($is_client)
        <button class="btn btn-sm btn-success create-conversation"><span class="glyphicon glyphicon-plus"></span> {{ trans('projectsquare::messages.add_conversation') }}</button>
    @endif
    <a href="{{ route('messages_index') }}" class="btn btn-sm btn-default pull-right"><span class="glyphicon glyphicon-list-alt"></span> {{ trans('projectsquare::messages.see_messages') }}</a>
    <span class="clearfix"></span>
</div>