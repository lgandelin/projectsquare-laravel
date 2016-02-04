<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('gateway::ticket_types.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::ticket_types.name_placeholder') }}" name="name" @if (isset($ticket_type_name))value="{{ $ticket_type_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('gateway::generic.valid') }}
        </button>
        <a href="{{ route('ticket_types_index') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($ticket_type_id))
        <input type="hidden" name="ticket_type_id" value="{{ $ticket_type_id }}" />
    @endif

    {!! csrf_field() !!}
</form>