<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="title">{{ trans('gateway::tickets.title') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::tickets.title_placeholder') }}" name="title" @if (isset($ticket_title))value="{{ $ticket_title }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('gateway::tickets.project') }}</label>
        @if (isset($projects))
            <select class="form-control" name="project_id">
                <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if (isset($ticket) && $ticket->project_id == $project->id)selected="selected"@endif>[{{ $project->client->name }}] {{ $project->name }}</option>
                @endforeach
            </select>
        @else
            <div class="info bg-info">Vous n'avez pas encore créé de projet</div>
        @endif
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
        <a href="{{ route('tickets_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($ticket_id))
        <input type="hidden" name="ticket_id" value="{{ $ticket_id }}" />
    @endif

    {!! csrf_field() !!}
</form>