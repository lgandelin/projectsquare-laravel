<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('projectsquare::ticket_statuses.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::ticket_statuses.name_placeholder') }}" name="name" @if (isset($ticket_status_name))value="{{ $ticket_status_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
        <a href="{{ \URL::previous() }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
    </div>

    @if (isset($ticket_status_id))
        <input type="hidden" name="ticket_status_id" value="{{ $ticket_status_id }}" />
    @endif

    {!! csrf_field() !!}
</form>