<div class="page-header">
    <h1>{{ trans('projectsquare::projects.infos') }}</h1>

    <a href="{{ route('projects_index') }}" class="btn back"></a>
</div>

<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.label') }}</label>
        <input required class="form-control" type="text" placeholder="{{ trans('projectsquare::projects.name_placeholder') }}" name="name" @if (isset($project_name))value="{{ $project_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.client') }}</label>
        <select class="form-control" name="client_id">
            <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @if (isset($project) && $project->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
            @endforeach
        </select>
        <div class="create-client-inline">
            <div class="step-1">Votre client ne fait pas encore partie de la liste ? <a href="#" id="create-client-btn">Créer un client</a></div>
            <div class="step-2">
                <input type="text" class="form-control" placeholder="Nom de la société" id="new-client-name" />
                <button class="btn valid" id="valid-new-client"><i class="glyphicon glyphicon-ok"></i> Valider</button>
                <a href="#" id="cancel-new-client">Annuler</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.color') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.color')
            ])
        </label>
        <input type="text" name="color" class="form-control colorpicker" data-control="saturation" placeholder="{{ trans('projectsquare::projects.color') }}"  @if (isset($project_color))value="{{ $project_color }}"@endif size="7">
    </div>

    <div class="form-group">
        <label for="status_id">{{ trans('projectsquare::projects.status') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.status')
            ])
        </label>
        <select class="form-control" name="status_id">
            <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
            <option value="{{ Webaccess\ProjectSquare\Entities\Project::IN_PROGRESS }}" @if (isset($project) && $project_status_id == Webaccess\ProjectSquare\Entities\Project::IN_PROGRESS || !isset($project_id))selected="selected"@endif>En cours</option>
            <option value="{{ Webaccess\ProjectSquare\Entities\Project::ARCHIVED }}" @if (isset($project) && $project_status_id == Webaccess\ProjectSquare\Entities\Project::ARCHIVED)selected="selected"@endif>Archivé</option>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($project_id))
        <input type="hidden" name="project_id" value="{{ $project_id }}" />
    @endif

    {!! csrf_field() !!}
</form>

@section('scripts')
    <script src="{{ asset('js/projects.js') }}"></script>
    <script>
        $('.colorpicker').minicolors({
            theme: 'bootstrap'
        });
    </script>
@endsection