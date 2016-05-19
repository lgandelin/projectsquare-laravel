<form action="{{ route('tickets_update') }}" method="post">
    <div class="row ticket-state">
        <div class="col-md-6">
            <div class="form-group">
                <label for="status_id">{{ trans('projectsquare::tickets.status') }}</label>
                @if (isset($ticket_status))
                <select class="form-control" name="status_id">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($ticket_status as $ticket_status)
                    <option value="{{ $ticket_status->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->status_id == $ticket_status->id)selected="selected"@endif>{{ $ticket_status->name }}</option>
                    @endforeach
                </select>
                @else
                <div class="info bg-info">{{ trans('projectsquare::tickets.no_ticket_status_yet') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="allocated_user_id">{{ trans('projectsquare::tickets.allocated_user') }}</label>
                @if (isset($users))
                <select class="form-control" name="allocated_user_id">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->allocated_user_id == $user->id)selected="selected"@endif>{{ $user->complete_name }}</option>
                    @endforeach
                </select>
                @else
                <div class="info bg-info">{{ trans('projectsquare::tickets.no_user_yet') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="comments">{{ trans('projectsquare::tickets.comments') }}</label>
                <textarea class="form-control" rows="4" placeholder="{{ trans('projectsquare::tickets.comments') }}" name="comments"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>
                <a href="{{ route('tickets_index') }}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.back') }}</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">{{ trans('projectsquare::tickets.due_date') }}</label>
                <input class="form-control datepicker" type="text" placeholder="{{ trans('projectsquare::tickets.due_date_placeholder') }}" name="due_date" @if (isset($ticket->states[0]->due_date))value="{{ $ticket->states[0]->due_date }}"@endif autocomplete="off" />
            </div>

            <div class="form-group">
                <label for="priority">{{ trans('projectsquare::tickets.priority') }}</label>
                <select class="form-control" name="priority">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->priority == $i)selected="selected"@endif>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="estimated_time">{{ trans('projectsquare::tickets.estimated_time') }}</label>
                <input class="form-control" type="time" name="estimated_time" placeholder="{{ trans('projectsquare::tickets.estimated_time') }}" value="@if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->estimated_time){{ $ticket->states[0]->estimated_time }}@endif" />
            </div>
        </div>

        @if (isset($ticket->id))
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
        @endif

        @if (isset($logged_in_user))
        <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
        @endif

        {!! csrf_field() !!}
    </div>
</form>