<div class="block last-messages"> 
    <div class="wrapper">
        <div class="block-content table-responsive">
            <h3>{{ trans('projectsquare::dashboard.last_messages') }}</h3>
             <a href="{{ route('messages_index') }}" class="all pull-right"></a>

            <table class="table table-striped">
                 <thead>
                    <tr>
                        <!--<th>#</th>-->    
                        <th>{{ trans('projectsquare::tickets.client') }} </th>
                        <th>{{ trans('projectsquare::messages.message') }} </th>
                        <th>{{ trans('projectsquare::messages.allocated_user') }} </th>
                        <th>{{ trans('projectsquare::messages.date') }} </th>
                        <th>{{ trans('projectsquare::messages.action') }} </th>
                     
                    </tr>
                </thead>

                <tbody>
                @foreach ($conversations as $conversation)
                    <tr class="conversation">
                   
                        <td align="center" style="border-left: 10px solid {{ $conversation->project->color }}">
                     {{ $conversation->project->client->name }}<br />{{ $conversation->title }}</strong><br></td>

                        <td>
                            <p>{{ $conversation->messages[sizeof($conversation->messages) - 1]->content }}</p>
                        </td>

                        <td>
                            @include('projectsquare::includes.avatar', [
                                'id' => $conversation->messages[sizeof($conversation->messages) - 1]->user->id,
                                'name' => $conversation->messages[sizeof($conversation->messages) - 1]->user->complete_name 
                            ])
                         
                        </td>

                        <td>
                           {{ date('d/m H:i', strtotime($conversation->messages[sizeof($conversation->messages) - 1]->created_at)) }}
                        </td>

                        <td>
                            <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-sm btn-primary see-more"></a>

                            <span class="submit">
                                <button class="btn btn-sm btn-success pull-right reply-message" data-id="{{ $conversation->id }}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span></button>
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="5">
                            <div class="message-inserted"></div>

                            <div class="message new-message" style="display:none">
                                <textarea class="form-control" placeholder="{{ trans('projectsquare::dashboard.your_message') }}" rows="4"></textarea>
                                <button class="btn btn-sm btn-default pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                                <button class="btn btn-sm btn-success pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ trans('projectsquare::generic.valid') }}</button>
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
 
</div>