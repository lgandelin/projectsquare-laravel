<div class="modal fade conversation-modal" id="conversation-{{ $conversation->id }}-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ __('projectsquare::messages.messages') }} > {{ $conversation->title }}</h4>
                </div>
                <div class="modal-body">
                    <div class="conversation" data-id="{{ $conversation->id }}">
                        <div class="messages">
                            @foreach ($conversation->messages as $i => $message)
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 message @if ($message->user->client_id)message-client" style="border-left: 10px solid{{ $conversation->project->color }} @endif">
                                    <div class="border">
                                        @include('projectsquare::includes.avatar', [
                                            'id' => $message->user->id,
                                            'name' => $message->user->complete_name
                                        ])
                                        <p class="message-content">{!! nl2br($message->content) !!}</p>
                                        <span class="date">Le {{ date('d/m à H:i', strtotime($message->created_at)) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="message-inserted"></div>

                        <div class=" col-lg-8 col-md-8 col-sm-12 col-xs-12 pull-right message new-message">
                            <textarea class="form-control" placeholder="Votre message" rows="5" autocomplete="off"></textarea>
                            <span class="border-new-message" style="border-top: 1px solid{{ $conversation->project->color }}"></span>
                            <button class="btn button pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ __('projectsquare::generic.cancel') }}</button>
                            <button class="btn valid pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ __('projectsquare::generic.valid') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>