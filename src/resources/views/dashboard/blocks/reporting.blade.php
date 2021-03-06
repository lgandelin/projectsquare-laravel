@if ($current_projects_reporting)
    <div class="reporting progress-template owl-carousel owl-theme">
        @foreach ($current_projects_reporting as $project)
            <div class="project item" style="background: {{ $project->color }};">
                {{ $project->name }}

                <span class="toggle-progress"></span>

                <span class="time-values">
                    &nbsp;
                    @if ($project->progress){{ $project->progress }}%@endif
                    @if ($project->differenceSpentEstimated != 0)
                        @if ($project->differenceSpentEstimated > 0)(+@elseif ($project->differenceSpentEstimated < 0)(@endif{{ $project->differenceSpentEstimated }}j)
                    @endif
                </span>

                <div class="project-team">
                    @foreach ($project->users as $user)
                        @include('projectsquare::includes.avatar', [
                            'id' => $user->id,
                            'name' => $user->complete_name
                        ])
                    @endforeach
                </div>

                <div class="project-progress" style="display: none;">
                    <table class="table table-bordered">
                        <tr>
                            <td>{{ __('projectsquare::progress.phases_tasks') }}</td>
                            <td>{{ __('projectsquare::progress.progress') }}</td>
                        </tr>

                        @foreach ($project->phases as $phase)
                            <tr>
                                <td class="phase">
                                    <span class="name">
                                        {{ $phase->name }}
                                    </span>

                                    <div class="tasks">
                                        @foreach ($phase->tasks as $task)
                                            <div class="task" style="@if ($task->statusID == Webaccess\ProjectSquare\Entities\Task::COMPLETED)background-color: #5497aa; @endif">
                                                <a href="{{ route('project_tasks_edit', ['uuid' => $project->id, 'task_uuid' => $task->id]) }}" title="Voir la tâche">
                                                    <div class="description">

                                                        @if (isset($task->allocatedUser))
                                                            @include('projectsquare::includes.avatar', [
                                                                'id' => $task->allocatedUser->id,
                                                                'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                                            ])
                                                        @endif

                                                        <span class="name">{{ $task->title }}</span>
                                                        @if ($task->estimatedTimeDays > 0)<span class="duration"> <strong>{{ __('projectsquare::dashboard.estimated_time') }}</strong> {{ $task->estimatedTimeDays }} {{ __('projectsquare::generic.days') }}</span> @endif
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="phase-value" width="20%">
                                    @if ($phase->progress !== null && $phase->progress !== ""){{ $phase->progress }} %@endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="text-align: right;">{{ __('projectsquare::generic.total') }}</td>
                            <td width="20%" class="total">@if ($project->progress !== null && $project->progress !== ""){{ $project->progress }} %@endif</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endif

<script src="{{ asset('js/vendor/owl.carousel.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $('.reporting').owlCarousel({
            loop:true,
            margin:25,
            nav:false,
            slideBy: 'page',
            responsive:{
                1:{
                    items: 1
                },
                800:{
                    items: 1
                },
                1000:{
                    items: 2
                },
                1200:{
                    items:3
                },
            }
        });

        //DASHBOARD - REPORTING WIDGET
        $('.reporting .project .toggle-progress').click(function(e) {
            e.preventDefault();

            $(this).closest('.project').find('.project-progress').slideToggle(200);
            $(this).toggleClass('opened');
        });

        $(document).mouseup(function(e)
        {
            var container = $(".reporting");

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('.project-progress').each(function() {
                    var toggle_icon = $(this).closest('.project').find('.toggle-progress');
                    if (toggle_icon.hasClass('opened')) {
                        toggle_icon.trigger('click');
                    }
                });
            }
        });
    });
</script>