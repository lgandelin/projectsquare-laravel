<div class="page-header">
    <h1>{{ trans('projectsquare::projects.tasks') }}</h1>
    <a href="{{ route('projects_index') }}" class="btn back"></a>
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

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/project-tasks.js') }}"></script>
@endsection