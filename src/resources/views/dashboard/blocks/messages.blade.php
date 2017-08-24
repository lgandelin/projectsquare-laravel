<div class="block last-messages"> 
    <div class="block-content table-responsive">
        <h3>{{ trans('projectsquare::dashboard.messages') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.messages')
            ])
            @if ($is_client)
                <a href="{{ route('project_messages', ['id' => $current_project->id]) }}" class="all pull-right" title="{{ trans('projectsquare::dashboard.conversations_list') }}"></a>
                <button class="btn add create-conversation pull-right" title="{{ trans('projectsquare::dashboard.add_conversation') }}"></button>
            @else
                <a href="{{ route('conversations_index') }}" class="all pull-right" title="{{ trans('projectsquare::dashboard.conversations_list') }}"></a>
            @endif

            <a href="#" class="glyphicon glyphicon-move move-widget pull-right" title="{{ trans('projectsquare::dashboard.move_widget') }}"></a>
        </h3>

        <table class="table table-striped">
             <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('projectsquare::messages.message') }} </th>
                    <th style="text-align:center">{{ trans('projectsquare::messages.author') }} </th>
                    <th>{{ trans('projectsquare::messages.date') }} </th>
                    <th>{{ trans('projectsquare::messages.action') }} </th>

                </tr>
            </thead>

            <tbody>
            @foreach ($conversations as $conversation)
                <tr class="conversation" id="conversation-{{ $conversation->id }}" data-id="{{ $conversation->id }}">

                    <td class="project-border" style="border-left: 10px solid {{ $conversation->project->color }}" ></td>
                    <td class="text-conversation" width="50%">
                       {{ str_limit($conversation->messages[sizeof($conversation->messages) - 1]->content, 100) }}
                    </td>

                    <td align="center">
                        @include('projectsquare::includes.avatar', [
                            'id' => $conversation->messages[sizeof($conversation->messages) - 1]->user->id,
                            'name' => $conversation->messages[sizeof($conversation->messages) - 1]->user->complete_name
                        ])
                    </td>

                    <td>
                       {{ date('d/m H:i', strtotime($conversation->messages[sizeof($conversation->messages) - 1]->created_at)) }}
                    </td>

                    <td align="center">
                        <!--<a href="{{ route('conversations_view', ['id' => $conversation->id]) }}" class="btn btn-sm btn-primary see-more" style="margin-right: 1rem"></a>-->
                        <button class="button-message pull-right reply-message" data-id="{{ $conversation->id }}" title="{{ trans('projectsquare::dashboard.see_conversation') }}"><span class="glyphicon-comment"></span></button>
                    </td>
                </tr>

                <tr class="conversation-reply" style="display:none" id="conversation-{{ $conversation->id }}-reply" data-id="{{ $conversation->id }}">
                    <td colspan="5">
                        <div class="message-inserted"></div>

                        <div class="message new-message">
                            <textarea class="form-control" placeholder="{{ trans('projectsquare::dashboard.your_message') }}" rows="4"></textarea>
                            <button class="btn btn-sm back pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                            <button class="btn btn-sm valid pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('projectsquare::generic.valid') }}</button>
                        </div>
                    </td>
                </tr>
                <tr></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach ($conversations as $conversation)
    @include('projectsquare::dashboard.conversation-modal', ['conversation' => $conversation])
@endforeach
