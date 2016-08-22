<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('projectsquare::ticket_types.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::ticket_types.name_placeholder') }}" name="name" @if (isset($ticket_type_name))value="{{ $ticket_type_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
        <a href="{{ \URL::previous() }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
    </div>

    @if (isset($ticket_type_id))
        <input type="hidden" name="ticket_type_id" value="{{ $ticket_type_id }}" />
    @endif

    {!! csrf_field() !!}
</form>