<form action="{{ route('tickets_update_infos') }}" method="post">
    <div class="row ticket-infos">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">{{ __('projectsquare::tickets.title') }}</label>
                <input class="form-control" disabled type="text" placeholder="{{ __('projectsquare::tickets.title_placeholder') }}" name="title" @if (isset($ticket->title))value="{{ $ticket->title }}"@endif />
            </div>

            <div class="form-group">
                <label for="project_id">{{ __('projectsquare::tickets.project') }}</label>
                @if (isset($projects))
                    <select disabled class="form-control" name="project_id" @if ($is_client) disabled @endif>
                        <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                        @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @if (isset($ticket) && $ticket->projectID == $project->id)selected="selected"@endif>@if (isset($project->client))[{{ $project->client->name }}]@endif {{ $project->name }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="info bg-info">{{ __('projectsquare::tickets.no_project_yet') }}</div>
                @endif
            </div>

             <div class="form-group">
                <label for="type_id">{{ __('projectsquare::tickets.type') }}</label>
                @if (isset($ticket_types))
                <select disabled class="form-control" name="type_id">
                    <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
                    @foreach ($ticket_types as $ticket_type)
                    <option value="{{ $ticket_type->id }}" @if (isset($ticket) && $ticket->typeID == $ticket_type->id)selected="selected"@endif>{{ $ticket_type->name }}</option>
                    @endforeach
                </select>
                @else
                <div class="info bg-info">{{ __('projectsquare::tickets.no_ticket_type_yet') }}</div>
                @endif
            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label for="description">{{ __('projectsquare::tickets.description') }}</label>
                <textarea disabled class="form-control" rows="4" placeholder="{{ __('projectsquare::tickets.description') }}" name="description">@if (isset($ticket->description)){{ $ticket->description }}@endif</textarea>
            </div>
        </div>
    </div>

    <div class="form-group ticket-infos-valid">
        <button type="submit" class="btn button update">
            <i class="glyphicon glyphicon-pencil"></i> {{ __('projectsquare::generic.modify') }}
        </button>

        <button type="submit" class="btn valid" style="display:none">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>

        <a href="#" class="btn-cancel" style="display:none">{{ __('projectsquare::generic.cancel') }}</a>

        <div class="notice notice-notification" style="display: none">
            {{ __('projectsquare::tickets.infos_update_no_notification') }}
        </div>
    </div>

    @if (isset($ticket->id))
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
    @endif

    {!! csrf_field() !!}
</form>

<script src="{{ asset('js/tickets.js') }}"></script>