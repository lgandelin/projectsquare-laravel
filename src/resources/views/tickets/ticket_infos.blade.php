<form action="{{ route('tickets_update_infos') }}" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">{{ trans('gateway::tickets.title') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('gateway::tickets.title_placeholder') }}" name="title" @if (isset($ticket->title))value="{{ $ticket->title }}"@endif />
            </div>

            <div class="form-group">
                <label for="project_id">{{ trans('gateway::tickets.project') }}</label>
                @if (isset($projects))
                <select class="form-control" name="project_id">
                    <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                    @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if (isset($ticket) && $ticket->project_id == $project->id)selected="selected"@endif>[{{ $project->client->name }}] {{ $project->name }}</option>
                    @endforeach
                </select>
                @else
                <div class="info bg-info">{{ trans('gateway::tickets.no_project_yet') }}</div>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('gateway::generic.valid') }}
                </button>
                <a href="{{ route('tickets_index') }}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('gateway::generic.back') }}</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="type_id">{{ trans('gateway::tickets.type') }}</label>
                @if (isset($ticket_types))
                <select class="form-control" name="type_id">
                    <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                    @foreach ($ticket_types as $ticket_type)
                    <option value="{{ $ticket_type->id }}" @if (isset($ticket) && $ticket->type_id == $ticket_type->id)selected="selected"@endif>{{ $ticket_type->name }}</option>
                    @endforeach
                </select>
                @else
                <div class="info bg-info">{{ trans('gateway::tickets.no_ticket_type_yet') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="description">{{ trans('gateway::tickets.description') }}</label>
                <textarea class="form-control" rows="4" placeholder="{{ trans('gateway::tickets.description') }}" name="description">@if (isset($ticket->description)){{ $ticket->description }}@endif</textarea>
            </div>
        </div>
    </div>

    @if (isset($ticket->id))
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
    @endif

    {!! csrf_field() !!}
</form>