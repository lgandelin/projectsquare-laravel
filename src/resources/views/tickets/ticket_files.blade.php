<div class="text-content">
    <div class="span7 offset1">
        @if (Session::has('success'))
            <div class="alert-box success">
                <h2>{!! Session::get('success') !!}</h2>
            </div>
        @endif
        
        <form method="post" action="{{ route('tickets_edit_file_upload') }}" enctype="multipart/form-data">
            <div class="control-group">
                <div class="controls">
                    <input type="file" name="images[]" multiple="true" />
                    <p class="errors">{!!$errors->first('images')!!}</p>
                    @if(Session::has('error'))
                        <p class="errors">{!! Session::get('error') !!}</p>
                    @endif
                </div>
            </div>

            {{ csrf_field() }}

            @if (isset($ticket->id))
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
            @endif

            <button type="submit" class="btn btn-success">Valider</button>
        </form>
    </div>
</div>
