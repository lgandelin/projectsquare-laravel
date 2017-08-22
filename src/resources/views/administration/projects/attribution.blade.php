<div class="page-header">
    <h1>{{ trans('projectsquare::projects.attribution') }}</h1>
    <a href="{{ route('projects_index') }}" class="btn back"></a>
</div>

<div class="attribution-template">

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

    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 pull-right">
            <div class="notice">
                <span class="title">Attribuez des tâches à vos collaborateurs</span>
                <ul>
                    <li>pour attribuer une tâche à un collaborateur, faites un "glisser-déposer" de la tâche directement dans le calendrier ci-dessous</li>
                    <li>les tâches planifiées de cette manière sont automatiquement ajoutées aux plannings des collaborateurs</li>
                    <li>une fois la tâche attribuée, il est possible de la désattribuer en cliquant sur l'icône <i class="glyphicon glyphicon-user"></i></li>
                    <li>si vous souhaitez replanifier une tâche (par exemple après une erreur ou une modification de durée), vous devrez la désattribuer puis la refaire glisser dans le planning du collaborateur</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="phases">
                @if (sizeof($phases) > 0)
                    @foreach ($phases as $phase)
                        <div class="phase" data-id="{{ $phase->id }}" data-name="{{ $phase->name }}">
                            <div class="phase-wrapper">
                                <span class="name">{{ $phase->name }}</span>
                            </div>

                            <div class="tasks">
                                @foreach ($phase->tasks as $task)
                                    <div class="task" data-id="{{ $task->id }}" data-name="{{ $task->title }}" data-phase="{{ $phase->id }}" data-duration="{{ $task->estimatedTimeDays }}">
                                        <div class="task-wrapper @if(isset($task->allocatedUserID)) allocated @endif">
                                            @if (isset($task->allocatedUser))
                                                @include('projectsquare::includes.avatar', [
                                                    'id' => $task->allocatedUser->id,
                                                    'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                                ])
                                            @endif
                                            <i class="glyphicon glyphicon-user unallocate-task" title="Désattribuer la tâche"></i>
                                            <i class="glyphicon glyphicon-move drag-task" title="Attribuer la tâche à un collaborateur"></i>
                                            <span class="name">{{ $task->title }}</span>
                                            @if ($task->estimatedTimeDays)<span class="duration">{{ $task->estimatedTimeDays }} j</span>@endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="info bg-info">Aucune phase / tâche insérée pour le moment.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="occupation-template">
        <form method="get">
            <div class="row">
                <h2>{{ trans('projectsquare::tasks.filters.filters') }}</h2>

                <div class="form-group col-md-2">
                    <select class="form-control" name="filter_role" id="filter_role">
                        <option value="">{{ trans('projectsquare::occupation.filters.by_role') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if ($filters['role'] == $role->id)selected="selected" @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <hr/>

        <div id="calendars">
            @include('projectsquare::management.occupation.includes.calendar')
        </div>
    </div>
</div>

<input type="hidden" id="project_id" value="{{ $project_id }}" />

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/occupation.js') }}"></script>
    <script src="{{ asset('administration/js/project-attribution.js') }}"></script>
@endsection