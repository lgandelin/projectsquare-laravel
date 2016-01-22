<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('gateway::projects.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::projects.name_placeholder') }}" name="name" @if (isset($project_name))value="{{ $project_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('gateway::projects.client') }}</label>
        @if (isset($clients))
            <select class="form-control" name="client_id">
                <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if (isset($project) && $project->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>
        @else
            <div class="info bg-info">Vous n'avez pas encore créé de clients</div>
        @endif
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
        <a href="{{ route('projects_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($project_id))
        <input type="hidden" name="project_id" value="{{ $project_id }}" />
    @endif

    {!! csrf_field() !!}
</form>