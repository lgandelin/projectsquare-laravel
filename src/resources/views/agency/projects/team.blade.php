<div class="page-header">
    <h1>{{ trans('projectsquare::projects.team') }}</h1>
</div>

<div class="team">
    <div class="phases">
        @foreach ($phases as $phase)
            <div class="phase" data-id="{{ $phase->id }}" data-name="{{ $phase->name }}">
                <div class="phase-wrapper">
                    <span class="name">{{ $phase->name }}</span>
                </div>

                <div class="tasks">
                    @foreach ($phase->tasks as $task)
                        <div class="task" data-id="{{ $task->id }}" data-name="{{ $task->title }}" data-phase="{{ $phase->id }}" data-duration="{{ $task->estimated_time_days }}">
                            <div class="task-wrapper">
                                <span class="name">{{ $task->title }}</span>
                                <span class="duration">{{ $task->estimated_time_days }}j</span>
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

                <div class="col-md-2">
                    <input class="btn button" type="submit" value="{{ trans('projectsquare::generic.valid') }}" />
                </div>
            </div>
        </form>

        <hr/>

        @include('projectsquare::occupation.includes.calendar')
    </div>
</div>

<input type="hidden" id="project_id" value="{{ $project_id }}" />

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/occupation.js') }}"></script>
    <script src="{{ asset('js/project-team.js') }}"></script>
@endsection