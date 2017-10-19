<a href="{{ route('projects_index') }}" class="btn back"></a>

<div class="attribution-template">

    <div class="middle-column">
        @foreach ($phases as $phase)
            <div class="parent phase" data-id="{{ $phase->id }}" data-name="{{ $phase->name }}">
                <div class="parent-wrapper">
                    <span class="name">{{ $phase->name }}</span>
                    <span class="childs-number">{{ sizeof($phase->tasks) }}</span>
                    <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
                </div>

                <div class="childs tasks">
                    @foreach ($phase->tasks as $task)
                        <div class="child task @if (isset($task->allocatedUser))allocated @endif" data-id="{{ $task->id }}" data-name="{{ $task->title }}" data-phase="{{ $phase->id }}" data-duration="{{ $task->estimatedTimeDays }}">
                            <div class="child-wrapper task-wrapper @if(isset($task->allocatedUserID)) allocated @endif">
                                <img class="default-avatar" class="avatar" src="{{ asset('img/default-avatar.jpg') }}" @if (isset($task->allocatedUser))style="display: none"@endif/>
                                @if (isset($task->allocatedUser))
                                    @include('projectsquare::includes.avatar', [
                                        'id' => $task->allocatedUser->id,
                                        'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                    ])
                                @endif
                                <span class="name">{{ $task->title }}</span>
                                <i class="fa fa-refresh unallocate-task" title="Désattribuer la tâche"></i>
                                <i class="glyphicon glyphicon-move drag-task" title="Attribuer la tâche à un collaborateur"></i>
                                @if ($task->estimatedTimeDays)<span class="duration">{{ $task->estimatedTimeDays }} j</span>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="project-attribution-content">
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

        <div class="occupation-template">
            <form method="get">
                <div class="row">
                    <h2>{{ __('projectsquare::tasks.filters.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_role" id="filter_role">
                            <option value="">{{ __('projectsquare::occupation.filters.by_role') }}</option>
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

            <div class="notice">
                <span class="title">{{ __('projectsquare::project_attribution.instructions.title') }}</span>
                <ul>
                    <li>{{ __('projectsquare::project_attribution.instructions.1') }}</li>
                    <li>{{ __('projectsquare::project_attribution.instructions.2') }}</li>
                    <li>{{ __('projectsquare::project_attribution.instructions.3') }} <i class="fa fa-refresh" style="margin-left: 0.75rem;
                    "></i></li>
                    <li>{{ __('projectsquare::project_attribution.instructions.4') }}/li>
                </ul>
            </div>
        </div>
    </div>

    <input type="hidden" id="project_id" value="{{ $project_id }}" />
</div>

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/occupation.js') }}"></script>
    <script src="{{ asset('js/administration/project-attribution.js') }}"></script>
@endsection