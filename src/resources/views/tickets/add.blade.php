@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li><a href="{{ route('tickets_index') }}">{{ trans('projectsquare::tickets.tickets_list') }}</a></li>
        <li class="active">{{ trans('projectsquare::tickets.add_ticket') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::tickets.add_ticket') }}</h1>
    </div>

    @if (isset($error))
        <div class="info bg-danger">
            {{ $error }}
        </div>
    @endif

    <form action="{{ route('tickets_store') }}" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">{{ trans('projectsquare::tickets.title') }}</label>
                    <input class="form-control" type="text" placeholder="{{ trans('projectsquare::tickets.title_placeholder') }}" name="title" @if (isset($ticket_title))value="{{ $ticket_title }}"@endif />
                </div>

                <div class="form-group">
                    <label for="project_id">{{ trans('projectsquare::tickets.project') }}</label>
                    @if (isset($projects))
                        <select class="form-control" name="project_id" @if (isset($ticket) && $ticket->id) disabled @endif>
                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @if ($current_project_id == $project->id)selected="selected"@endif>[{{ $project->client->name }}] {{ $project->name }}</option>
                        @endforeach
                        </select>
                    @else
                            <div class="info bg-info">{{ trans('projectsquare::tickets.no_project_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">{{ trans('projectsquare::tickets.description') }}</label>
                    <textarea class="form-control" rows="12" placeholder="{{ trans('projectsquare::tickets.description') }}" name="description"></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="{{ trans('projectsquare::generic.valid') }}" />
                    <a href="{{ route('tickets_index') }}" class="btn btn-default">{{ trans('projectsquare::generic.back') }}</a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="type_id">{{ trans('projectsquare::tickets.type') }}</label>
                    @if (isset($ticket_types))
                        <select class="form-control" name="type_id">
                            <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                            @foreach ($ticket_types as $ticket_type)
                            <option value="{{ $ticket_type->id }}">{{ $ticket_type->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="info bg-info">{{ trans('projectsquare::tickets.no_ticket_type_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="title">{{ trans('projectsquare::tickets.due_date') }}</label>
                    <input class="form-control datepicker" type="text" placeholder="{{ trans('projectsquare::tickets.due_date_placeholder') }}" name="due_date" autocomplete="off" />
                </div>

                <div class="form-group">
                    <label for="status_id">{{ trans('projectsquare::tickets.status') }}</label>
                    @if (isset($ticket_status))
                        <select class="form-control" name="status_id">
                            <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                            @foreach ($ticket_status as $ticket_status)
                                <option value="{{ $ticket_status->id }}">{{ $ticket_status->name }}</option>
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
                            <option value="{{ $user->id }}">{{ $user->complete_name }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="info bg-info">{{ trans('projectsquare::tickets.no_user_yet') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="priority">{{ trans('projectsquare::tickets.priority') }}</label>
                    <select class="form-control" name="priority">
                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="estimated_time">{{ trans('projectsquare::tickets.estimated_time') }}</label>
                    <input class="form-control" type="time" name="estimated_time" placeholder="{{ trans('projectsquare::tickets.estimated_time') }}" />
                </div>
            </div>
        </div>

        @if (isset($logged_in_user))
          <input type="hidden" name="author_user_id" value="{{ $logged_in_user->id }}" />
        @endif

        {!! csrf_field() !!}
    </form>
@endsection