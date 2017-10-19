<div class="page-header">
    <h1>{{ __('projectsquare::projects.infos') }}</h1>

    <a href="{{ route('projects_index') }}" class="btn back"></a>
</div>

@if (isset($error))
    <div class="info bg-danger">
        {{ $error }}
    </div>
@endif

@if (isset($confirmation))
    <div class="info bg-success">
        {{ $confirmation }}
    </div>
@endif

<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ __('projectsquare::projects.label') }}</label>
        <input required class="form-control" type="text" placeholder="{{ __('projectsquare::projects.name_placeholder') }}" name="name" @if (isset($project_name))value="{{ $project_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ __('projectsquare::projects.client') }}</label>
        <select class="form-control" name="client_id">
            <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @if (isset($project) && $project->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
            @endforeach
        </select>
        <div class="create-client-inline">
            <div class="step-1">{{ __('projectsquare::project_infos.client_not_in_list') }}<a href="#" id="create-client-btn">{{ __('projectsquare::project_infos.create_client') }}</a></div>
            <div class="step-2">
                <input type="text" class="form-control" placeholder="Nom de la société" id="new-client-name" />
                <button class="btn valid" id="valid-new-client"><i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}</button>
                <a href="#" id="cancel-new-client">{{ __('projectsquare::generic.cancel') }}</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="name">{{ __('projectsquare::projects.color') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.color')
            ])
        </label>
        <input type="text" name="color" class="form-control colorpicker" data-control="saturation" placeholder="{{ __('projectsquare::projects.color') }}"  @if (isset($project_color))value="{{ $project_color }}"@endif size="7">
    </div>

    <div class="form-group">
        <label for="status_id">{{ __('projectsquare::projects.status') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.status')
            ])
        </label>
        <select class="form-control" name="status_id">
            <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
            <option value="{{ Webaccess\ProjectSquare\Entities\Project::IN_PROGRESS }}" @if (isset($project) && $project_status_id == Webaccess\ProjectSquare\Entities\Project::IN_PROGRESS || !isset($project_id))selected="selected"@endif>En cours</option>
            <option value="{{ Webaccess\ProjectSquare\Entities\Project::ARCHIVED }}" @if (isset($project) && $project_status_id == Webaccess\ProjectSquare\Entities\Project::ARCHIVED)selected="selected"@endif>Archivé</option>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($project_id))
        <input type="hidden" name="project_id" value="{{ $project_id }}" />
    @endif

    {!! csrf_field() !!}
</form>

@section('scripts')
    <script src="{{ asset('js/administration/project-infos.js') }}"></script>
    <script>
        $('.colorpicker').minicolors({
            theme: 'bootstrap'
        });
    </script>
@endsection