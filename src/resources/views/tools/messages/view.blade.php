@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates message-view-template">
            <div class="page-header">
                <h1>{{ $conversation->title }}</h1>
                <a href="{{ $back_link }}" class="btn back"></a>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="conversation">
                    @foreach ($conversation->messages as $i => $message)
                        <tr>
                            <div class="messages">
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 message 
                                @if ($message->user->client_id)message-client" style="border-left: 10px solid{{ $conversation->project->color }} @endif">
                                    <div class="border">
                                        @include('projectsquare::includes.avatar', [
                                            'id' => $message->user->id,
                                            'name' => $message->user->complete_name
                                        ])
                                        <p class="message-content">{!! nl2br($message->content) !!}</p>
                                         <span class="date">Le {{ date('d/m à H:i', strtotime($message->created_at)) }}</span>
                                     </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach   

                    <div class="message-inserted"></div>

                    <div class=" col-lg-7 col-md-7 col-sm-12 col-xs-12 pull-right message new-message" style="display:none">
                        <textarea class="form-control" placeholder="Votre message" rows="6" autocomplete="off"></textarea>
                        <span class="border-new-message" style="border-top: 1px solid{{ $conversation->project->color }}"></span>
                        <!--<a class="img-new-message img-join" href=""></a>
                        <a class="img-new-message img-emoji" href=""></a>-->
                        <button class="btn button pull-right cancel-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem"><span class="glyphicon glyphicon-arrow-left"></span> {{ __('projectsquare::generic.cancel') }}</button>
                        <button class="btn valid pull-right valid-message" data-id="{{ $conversation->id }}" style="margin-top:1.5rem; margin-right: 1rem"><span class="glyphicon glyphicon-ok"></span> {{ __('projectsquare::generic.valid') }}</button>
                    </div>

                    <div class="submit">
                        <button class="btn button pull-right reply-message" data-id="{{ $message->id }}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span> {{ __('projectsquare::messages.reply_message') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('projectsquare::dashboard.new-message')

    <script src="{{ asset('js/messages.js') }}"></script>
@endsection