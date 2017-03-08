<div class="page-header">
    <h1>{{ trans('projectsquare::projects.attribution') }}</h1>
    <a href="{{ route('projects_index') }}" class="btn back"></a>
</div>

<div class="attribution-template">
    <div class="phases">
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
    <script src="{{ asset('js/project-attribution.js') }}"></script>
@endsection