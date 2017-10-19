<div class="project-tasks-template">

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

    <div class="page-header">
        <h1>{{ trans('projectsquare::projects.tasks') }}</h1>
        <a href="{{ route('projects_index') }}" class="btn back"></a>
        <span class="button-import-phases-tasks">{{ trans('projectsquare::generic.import') }} <i class="glyphicon glyphicon-save"></i>
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
                        <span class="phase-duration"><span class="value">{{ $phase->estimatedDuration }}</span> {{ trans('projectsquare::generic.days') }}</span>
                    </div>

                    <div class="tasks">
                        @foreach ($phase->tasks as $task)
                            <div class="task" data-id="{{ $task->id }}" data-phase="{{ $phase->id }}">
                                <div class="task-wrapper">
                                    <input type="text" class="input-task-name" value="{{ $task->title }}" />
                                    <a tabindex="-1" href="#" class="btn cancel delete-task"></a>
                                    <input class="input-task-duration" type="text" placeholder="{{ trans('projectsquare::project_tasks.duration_in_days') }}" value="{{ $task->estimatedTimeDays }}" />
                                </div>
                            </div>
                        @endforeach

                        <div class="placeholder add-task">{{ trans('projectsquare::project_tasks.add_task') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="placeholder add-phase">{{ trans('projectsquare::project_tasks.add_phase') }}</div>

        <span class="loading" style="display: none">{{ trans('projectsquare::project_tasks.saving') }}</span>
        <button class="btn valid-phases"><i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}</button>
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
                        <h4 class="modal-title">{{ trans('projectsquare::project_tasks.import_tasks_and_phases') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="notice">
                                <span class="title">{{ trans('projectsquare::project_tasks.paste_your_list_here') }}</span>
                                <ul>
                                    <li>{{ trans('projectsquare::project_tasks.import_instructions.1') }} "<span class="highlight">#</span>"</li>
                                    <li>{{ trans('projectsquare::project_tasks.import_instructions.2') }}</li>
                                    <li>{{ trans('projectsquare::project_tasks.import_instructions.3') }} "<span class="highlight">;</span>" {{ trans('projectsquare::project_tasks.import_instructions.4') }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <textarea name="text" data-placeholder="{{ trans('projectsquare::project_tasks.import_textarea_placeholder') }}" rows="10"></textarea>
                        </div>

                        <input type="hidden" name="project_id" value="{{ $project_id }}" />
                        {{ csrf_field() }}
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn valid"><i class="glyphicon glyphicon-save"></i> {{ trans('projectsquare::generic.import') }}</button>
                        <button type="button" class="btn button" data-dismiss="modal"><span class="glyphicon glyphicon-arrow-left"></span> {{ trans('projectsquare::generic.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('js/administration/project-tasks.js') }}"></script>
@endsection