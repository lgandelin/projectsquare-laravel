@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'reporting'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.reporting') }}</h1>

            @if (sizeof($tasks) > 0)
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <h3>{{ trans('projectsquare::reporting.task_statuses') }}</h3>
                        <ul>
                            <li><strong style="color: #d9534f">{{ trans('projectsquare::reporting.todo') }} :</strong> {{ $todo_tasks_count }} {{ trans('projectsquare::reporting.tasks') }}
                            <li><strong style="color: #f0ad4e">{{ trans('projectsquare::reporting.in_progress') }} : </strong> {{ $in_progress_tasks_count }} {{ trans('projectsquare::reporting.tasks') }}
                            <li><strong style="color: #5cb85c">{{ trans('projectsquare::reporting.finished') }} : </strong> {{ $completed_tasks_count }} {{ trans('projectsquare::reporting.tasks') }}
                            <li><strong>{{ trans('projectsquare::reporting.total') }} : </strong> {{ sizeof($tasks) }} {{ trans('projectsquare::reporting.tasks') }}
                        </ul>
                    </div>

                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                        <div class="progress" style="margin-top: 7rem;">
                            @if ($in_progress_tasks_count > 0)
                                <div class="progress-bar progress-bar-danger" style="width: {{ floor($todo_tasks_count * 100 / sizeof($tasks)) }}%">
                                    {{ trans('projectsquare::reporting.todo') }} ({{ $todo_tasks_count }} {{ trans('projectsquare::reporting.tasks') }})
                                </div>
                            @endif

                            @if ($in_progress_tasks_count > 0)
                                <div class="progress-bar progress-bar-warning" style="width: {{ floor($in_progress_tasks_count * 100 / sizeof($tasks)) }}%">
                                    {{ trans('projectsquare::reporting.in_progress') }} ({{ $in_progress_tasks_count }} {{ trans('projectsquare::reporting.tasks') }})
                                </div>
                            @endif

                            @if ($completed_tasks_count > 0)
                                <div class="progress-bar progress-bar-success" style="width: {{ floor($completed_tasks_count * 100 / sizeof($tasks)) }}%">
                                    {{ trans('projectsquare::reporting.finished') }} ({{ $completed_tasks_count }} {{ trans('projectsquare::reporting.tasks') }})
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{ trans('projectsquare::reporting.no_task_yet') }}
            @endif
            
            <hr>

            @if (sizeof($tickets) > 0)
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <h3>{{ trans('projectsquare::reporting.ticket_statuses') }}</h3>
                        <ul>
                            @foreach($ticket_statuses as $ticket_status)
                                <li><strong style="color: {{ $ticket_status->color }}">{{ $ticket_status->name }} :</strong> {{ $ticket_status->count }} {{ trans('projectsquare::reporting.tickets') }}</li>
                            @endforeach
                            <li><strong>{{ trans('projectsquare::reporting.total') }} : </strong> {{ sizeof($tickets) }} {{ trans('projectsquare::reporting.tickets') }}
                        </ul>
                    </div>

                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                        <div class="progress" style="margin-top: 7rem;">
                            @foreach($ticket_statuses as $ticket_status)
                                <div class="progress-bar" style="width: {{ floor($ticket_status->count * 100 / sizeof($tickets)) }}%; background: {{ $ticket_status->color }}">
                                    {{ $ticket_status->name }} ({{ $ticket_status->count }} {{ trans('projectsquare::reporting.tickets') }})
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{ trans('projectsquare::reporting.no_ticket_yet') }}
            @endif

            <hr>

            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <h3>{{ trans('projectsquare::reporting.time_tracking') }}</h3>

                        <ul>
                            <li><strong>{{ trans('projectsquare::reporting.total_tasks_estimated_time') }} :</strong> @if ($total_tasks_estimated_time_days > 0){{ $total_tasks_estimated_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($total_tasks_estimated_time_hours > 0){{ $total_tasks_estimated_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</li>
                            <li><strong>{{ trans('projectsquare::reporting.total_tasks_spent_time') }} :</strong> @if ($total_tasks_spent_time_days > 0){{ $total_tasks_spent_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($total_tasks_spent_time_hours > 0){{ $total_tasks_spent_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</li>
                            <li><strong>{{ trans('projectsquare::reporting.total_tickets_estimated_time') }} :</strong> @if ($total_tickets_estimated_time_days > 0){{ $total_tickets_estimated_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($total_tickets_estimated_time_hours > 0){{ $total_tickets_estimated_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</li>
                            <li><strong>{{ trans('projectsquare::reporting.total_tickets_spent_time') }} :</strong> @if ($total_tickets_spent_time_days > 0){{ $total_tickets_spent_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($total_tickets_spent_time_hours > 0){{ $total_tickets_spent_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif</li>
                            <li><strong>{{ trans('projectsquare::reporting.project_scheduled_time') }} :</strong> {{ $project->scheduledTime }} {{ trans('projectsquare::generic.days') }}</li>
                        </ul>
                    </div>

                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                        <div class="progress" style="margin-top: 7rem;">
                            <div class="progress-bar" style="width: {{ $tasks_spent_time_percentage }}%">
                                {{ ucfirst(trans('projectsquare::reporting.tasks')) }} : @if ($total_tasks_spent_time_days > 0){{ $total_tasks_spent_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($total_tasks_spent_time_hours > 0){{ $total_tasks_spent_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif
                            </div>

                            <div class="progress-bar progress-bar-warning" style="width: {{ $tickets_spent_time_percentage }}%">
                                {{ ucfirst(trans('projectsquare::reporting.tickets')) }} : @if ($total_tickets_spent_time_days > 0){{ $total_tickets_spent_time_days }} {{ trans('projectsquare::generic.days') }}@endif @if ($total_tickets_spent_time_hours > 0){{ $total_tickets_spent_time_hours }} {{ trans('projectsquare::generic.hours') }}@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-lg-12">
                    <h3>{{ trans('projectsquare::reporting.indicators') }}</h3>

                    <div class="col-lg-3">
                        {{ trans('projectsquare::reporting.tasks_spent_time_percentage') }} : <span style="font-size: 25px">{{ $tasks_spent_time_percentage }}%</span>
                    </div>

                    <div class="col-lg-3">
                        {{ trans('projectsquare::reporting.tickets_spent_time_percentage') }} : <span style="font-size: 25px">{{ $tickets_spent_time_percentage }}%</span>
                    </div>

                    <div class="col-lg-3">
                        {{ trans('projectsquare::reporting.progress_percentage') }} : <span style="font-size: 25px">{{ $progress_percentage }}%</span>
                    </div>

                    <div class="col-lg-3">
                        {{ trans('projectsquare::reporting.profitability_percentage') }} : <span style="font-size: 25px">@if ($profitability_percentage >= 0)+@endif{{ $profitability_percentage }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection