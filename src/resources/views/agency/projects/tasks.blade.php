<div class="page-header">
    <h1>{{ trans('projectsquare::projects.tasks') }}</h1>
</div>

<div class="phases-list">
    <div class="phases">
        @foreach ($phases as $phase)
            <div class="phase" data-id="{{ $phase->id }}" data-name="{{ $phase->name }}">
                <div class="phase-wrapper">
                    <span class="name">{{ $phase->name }}</span>
                    <a href="#" class="btn cancel btn-delete delete-phase"></a>
                    <span class="glyphicon glyphicon-triangle-top toggle-tasks"></span>
                </div>

                <div class="tasks">
                    <div class="task" data-id="task-1" data-name="Tâche 1" data-phase="{{ $phase->id }}">
                        <div class="task-wrapper">
                            <span class="name">Tâche 1</span>
                            <a href="#" class="btn cancel delete-task"></a>
                            <input class="input-task-duration" type="text" placeholder="durée en j." />
                        </div>
                    </div>

                    <div class="task" data-id="task-2" data-name="Tâche 2" data-phase="{{ $phase->id }}">
                        <div class="task-wrapper">
                            <span class="name">Tâche 2</span>
                            <a href="#" class="btn cancel delete-task"></a>
                            <input class="input-task-duration" type="text" placeholder="durée en j." />
                        </div>
                    </div>

                    <div class="placeholder add-task">Ajouter une tâche</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="placeholder add-phase">Ajouter une phase</div>
</div>

@include ('projectsquare::templates.new-phase')
@include ('projectsquare::templates.new-task')

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/tasks.js') }}"></script>
@endsection