<div class="middle-column">

    <div class="parent">
        <div class="parent-wrapper">
            <span class="name">Tickets</span>
            <span class="childs-number">{{ sizeof($tickets) }}</span>
            <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
        </div>

        @if (sizeof($tickets) > 0)
            <div class="childs tickets-list">
                @foreach ($tickets as $ticket)
                    <div class="child ticket" id="ticket-{{ $ticket->id }}" data-id="{{ $ticket->id }}" data-project="@if (isset($ticket->project)){{ $ticket->project->id }}@endif" data-ticket="{{ $ticket->id }}" data-color="@if (isset($ticket->project)){{ $ticket->project->color }}@endif" data-event='{"title":"{{ $ticket->title }}"}' data-duration="{{ Webaccess\ProjectSquareLaravel\Tools\PlanningTool::convertHoursToFullCalendar($ticket->last_state->estimated_time_hours) }}">
                        <div class="child-wrapper">
                            @if (isset($ticket->project))
                                <span class="project" style="background: {{ $ticket->project->color }}">{{ $ticket->project->name }}</span>
                            @endif

                            @if ($ticket->last_state->estimated_time_hours)
                                    <span class="duration">{{ $ticket->last_state->estimated_time_hours }} h</span>
                            @else
                                <span class="duration">-</span>
                            @endif

                            @if (isset($ticket->last_state) && isset($ticket->last_state->priority))
                                <span class="priority priority-{{ $ticket->last_state->priority }}" title="{{ trans('projectsquare::generic.priority-' . $ticket->last_state->priority) }}"></span>
                            @endif

                            <span class="name">{{ $ticket->title }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="parent">
        <div class="parent-wrapper">
            <span class="name">Tâches</span>
            <span class="childs-number">{{ sizeof($tasks) }}</span>
            <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
        </div>

        @if (sizeof($tasks) > 0)
            <div class="childs tasks-list">
                @foreach ($tasks as $task)
                    <div class="task child" id="task-{{ $task->id }}" data-id="{{ $task->id }}" data-project="@if (isset($task->project)){{ $task->project->id }}@endif" data-task="{{ $task->id }}" data-color="@if (isset($task->project)){{ $task->project->color }}@endif" data-event='{"title":"{{ $task->title }}"}' data-duration="{{ Webaccess\ProjectSquareLaravel\Tools\PlanningTool::convertDaysToFullCalendar($task->estimated_time_days) }}">
                        <div class="child-wrapper">
                            @if (isset($task->project))
                                <span class="project" style="background: {{ $task->project->color }}">{{ $task->project->name }}</span>
                            @endif

                            @if ($task->estimated_time_days)<span class="duration">{{ $task->estimated_time_days }} j</span>@endif

                            <span class="name">{{ $task->title }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<input type="hidden" class="tickets-current-project" />
<input type="hidden" class="tickets-current-ticket" />

<input type="hidden" class="tasks-current-project" />
<input type="hidden" class="tasks-current-task" />

<script id="ticket-template" type="text/x-handlebars-template">
    <div id="ticket-@{{id}}"
         data-id="@{{id}}"
         data-project="@{{project_id}}"
         data-ticket="@{{id}}"
         data-color="@{{color}}"
         data-event='{"title":"@{{title}}"}'
         data-duration="02:00"
         class="ticket fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="background: @{{color}}; margin-bottom: 1rem; width: 90%; border: none !important;"
            >
        <div class="fc-content"><div class="fc-title">
                @{{title}}
                <span class="unallocate-ticket glyphicon glyphicon-remove pull-right"></span>
            </div></div>
    </div>
</script>

<script id="task-template" type="text/x-handlebars-template">
    <div id="task-@{{id}}"
         data-id="@{{id}}"
         data-project="@{{project_id}}"
         data-task="@{{id}}"
         data-color="@{{color}}"
         data-event='{"title":"@{{title}}"}'
         data-duration="02:00"
         class="task fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable" style="background: @{{color}}; margin-bottom: 1rem; width: 90%; border: none !important;"
            >
        <div class="fc-content"><div class="fc-title">
                @{{title}}
                <span class="unallocate-task glyphicon glyphicon-remove pull-right"></span>
            </div></div>
    </div>
</script>