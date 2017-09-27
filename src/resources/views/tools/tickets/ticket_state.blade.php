<form action="{{ route('tickets_update') }}" method="post">
    <div class="row ticket-state">
        <div class="col-md-6">
            <div class="form-group">
                <label for="status_id">{{ trans('projectsquare::tickets.status') }}</label>
                @if (isset($ticket_status))
                <select class="form-control" name="status_id">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @foreach ($ticket_status as $ticket_status)
                    <option value="{{ $ticket_status->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->statusID == $ticket_status->id)selected="selected"@endif>{{ $ticket_status->name }}</option>
                    @endforeach
                </select>
                @else
                <div class="info bg-info">{{ trans('projectsquare::tickets.no_ticket_status_yet') }}</div>
                @endif
            </div>

            @if (!$is_client)
                <div class="form-group">
                    <label for="allocated_user_id">{{ trans('projectsquare::tickets.allocated_user') }}</label>
                    @if (isset($users))
                    <select class="form-control" name="allocated_user_id">
                        <option value="0">{{ trans('projectsquare::generic.choose_value') }}</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->allocatedUserID == $user->id)selected="selected"@endif>{{ $user->complete_name }}</option>
                        @endforeach
                    </select>
                    @else
                    <div class="info bg-info">{{ trans('projectsquare::tickets.no_user_yet') }}</div>
                    @endif
                </div>
            @else
                <input type="hidden" name="allocated_user_id" value="{{ $ticket->states[0]->allocatedUserID }}">
            @endif

            <div class="form-group">
                <label for="comments">{{ trans('projectsquare::tickets.comments') }}</label>
                <textarea class="form-control" rows="4" placeholder="{{ trans('projectsquare::tickets.comments') }}" name="comments"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn valid">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">{{ trans('projectsquare::tickets.due_date') }}</label>
                <input class="form-control datepicker" type="text" placeholder="{{ trans('projectsquare::tickets.due_date_placeholder') }}" name="due_date" @if (isset($ticket->states[0]->dueDate))value="{{ $ticket->states[0]->dueDate }}"@endif autocomplete="off" />
            </div>

            <div class="form-group">
                <label for="priority">{{ trans('projectsquare::tickets.priority') }}</label>
                <select class="form-control" name="priority">
                    <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                    @for ($i = 1; $i <= 3; $i++)
                    <option value="{{ $i }}" @if (isset($ticket) && isset($ticket->states[0]) && $ticket->states[0]->priority == $i)selected="selected"@endif>{{ trans('projectsquare::generic.priority-' . $i) }}</option>
                    @endfor
                </select>
            </div>

            @if (!$is_client)
                <div class="form-group">
                    <div style="display: inline-block">
                        <label for="estimated_time">{{ trans('projectsquare::tasks.estimated_time') }}</label><br>
                        <input class="form-control" type="text" name="estimated_time_hours" style="width: 6rem; margin-left: 1rem; margin-right: 0.5rem;" value="@if (isset($ticket) && isset($ticket->states[0]) && isset($ticket->states[0]->estimatedTimeHours)){{ $ticket->states[0]->estimatedTimeHours }}@endif" /> {{ trans('projectsquare::generic.hours') }}
                    </div>

                    <div style="display: inline-block; margin-left: 5rem;">
                        <label for="estimated_time">{{ trans('projectsquare::tasks.spent_time') }}</label><br>
                        <input class="form-control" type="text" name="spent_time_hours" style="width: 6rem; margin-left: 1rem; margin-right: 0.5rem;" value="@if (isset($ticket) && isset($ticket->states[0]) && isset($ticket->states[0]->spentTimeHours)){{ $ticket->states[0]->spentTimeHours }}@endif" /> {{ trans('projectsquare::generic.hours') }}
                    </div>
                </div>
            @endif
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