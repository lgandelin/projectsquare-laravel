<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('gateway::ticket_statuses.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::ticket_statuses.name_placeholder') }}" name="name" @if (isset($ticket_status_name))value="{{ $ticket_status_name }}"@endif />
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
        <a href="{{ route('ticket_statuses_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($ticket_status_id))
        <input type="hidden" name="ticket_status_id" value="{{ $ticket_status_id }}" />
    @endif

    {!! csrf_field() !!}
</form>