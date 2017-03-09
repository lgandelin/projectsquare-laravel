<div class="project-tasks-template">
    <div class="page-header">
        <h1>{{ trans('projectsquare::projects.tasks') }}</h1>
        <a href="{{ route('projects_index') }}" class="btn back"></a>
        <span class="button-import-phases-tasks">Importer <i class="glyphicon glyphicon-save"></i>
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.import_phases_and_tasks')
            ])
        </span>
    </div>

    <div class="project-tasks">
        <div class="phases">
            @foreach ($phases as $phase)
                <div class="phase" data-id="{{ $phase->id }}" data-duration="{{ $phase->estimatedDuration }}">
                    <div class="phase-wrapper">
                        <input type="text" class="input-phase-name" value="{{ $phase->name }}" />
                        <a tabindex="-1" href="#" class="btn cancel btn-delete delete-phase"></a>
                        <span class="glyphicon glyphicon-triangle-top toggle-tasks"></span>
                        <span class="phase-duration"><span class="value">{{ $phase->estimatedDuration }}</span> jour(s)</span>
                    </div>

                    <div class="tasks">
                        @foreach ($phase->tasks as $task)
                            <div class="task" data-id="{{ $task->id }}" data-phase="{{ $phase->id }}">
                                <div class="task-wrapper">
                                    <input type="text" class="input-task-name" value="{{ $task->title }}" />
                                    <a tabindex="-1" href="#" class="btn cancel delete-task"></a>
                                    <input class="input-task-duration" type="text" placeholder="durée en j." value="{{ $task->estimatedTimeDays }}" />
                                </div>
                            </div>
                        @endforeach

                        <div class="placeholder add-task">Ajouter une tâche</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="placeholder add-phase">Ajouter une phase</div>

        <span class="loading" style="display: none">Sauvegarde ...</span>
        <button class="btn valid-phases"><i class="glyphicon glyphicon-ok"></i> Valider</button>
    </div>

    <input type="hidden" id="phase_ids_to_delete" value="" />
    <input type="hidden" id="task_ids_to_delete" value="" />
    <input type="hidden" id="project_id" value="{{ $project_id }}" />

    @include ('projectsquare::templates.new-phase')
    @include ('projectsquare::templates.new-task')

    <div class="modal fade" id="import-phases-tasks-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('projects_import_phases_and_tasks_from_text') }}" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Importez vos phases et tâches</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="notice">
                                <span class="title">Collez votre liste ici</span>
                                <ul>
                                    <li>les phases doivent être préfixées par un "<span class="highlight">#</span>"</li>
                                    <li>les phases et tâches doivent être sur une seule ligne</li>
                                    <li>il est possible de spécifier une durée estimée aux tâches en rajoutant un "<span class="highlight">;</span>" suivi de la durée (en chiffres)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <textarea name="text" data-placeholder="# Webdesign
Création des mockups; 1
Ergonomie; 1
Déclinaisons webdesign; 3.5

# Développement
Création de la structure du site et des pages; 1.5
Intégration; 3.5
Tests et livraison; 0.5" rows="10"></textarea>
                        </div>

                        <input type="hidden" name="project_id" value="{{ $project_id }}" />
                        {{ csrf_field() }}
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn valid"><i class="glyphicon glyphicon-save"></i> Importer</button>
                        <button type="button" class="btn button" data-dismiss="modal"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/project-tasks.js') }}"></script>
@endsection