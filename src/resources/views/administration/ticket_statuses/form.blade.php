<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('projectsquare::ticket_statuses.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::ticket_statuses.name_placeholder') }}" name="name" @if (isset($ticket_status_name))value="{{ $ticket_status_name }}"@endif />
    </div>


    <div class="form-group">
        <label for="include_in_planning">{{ trans('projectsquare::ticket_statuses.include_in_planning') }}</label><br/>
        Oui <input type="radio" placeholder="{{ trans('projectsquare::ticket_statuses.include_in_planning') }}" name="include_in_planning" value="y" @if ((isset($ticket_status_include_in_planning) && $ticket_status_include_in_planning) || !isset($ticket_status_include_in_planning)) checked @endif autocomplete="off" />
        Non <input type="radio" placeholder="{{ trans('projectsquare::ticket_statuses.include_in_planning') }}" name="include_in_planning" value="n" @if (isset($ticket_status_include_in_planning) && !$ticket_status_include_in_planning) checked @endif autocomplete="off" />
    </div>


    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($ticket_status_id))
        <input type="hidden" name="ticket_status_id" value="{{ $ticket_status_id }}" />
    @endif

    {!! csrf_field() !!}
</form>