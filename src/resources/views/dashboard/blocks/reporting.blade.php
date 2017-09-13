@if ($current_projects_reporting)
    <div class="reporting progress-template">
        @foreach ($current_projects_reporting as $project)
            <div class="project" style="background: {{ $project->color }};">
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
                            <td>{{ trans('projectsquare::progress.phases_tasks') }}</td>
                            <td>{{ trans('projectsquare::progress.progress') }}</td>
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
                                                <div class="description">

                                                    @if (isset($task->allocatedUser))
                                                        @include('projectsquare::includes.avatar', [
                                                            'id' => $task->allocatedUser->id,
                                                            'name' => $task->allocatedUser->firstName . ' ' . $task->allocatedUser->lastName
                                                        ])
                                                    @endif

                                                    <span class="name">{{ $task->title }}</span>
                                                    @if ($task->estimatedTimeDays > 0)<span class="duration"> <strong>Temps estimé :</strong> {{ $task->estimatedTimeDays }} jour(s)</span> @endif
                                                </div>
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
                            <td style="text-align: right;">Total</td>
                            <td width="20%" class="total">@if ($project->progress !== null && $project->progress !== ""){{ $project->progress }} %@endif</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endif

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick-theme.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick.min.js"></script>

<script>
    $(document).ready(function() {
        $('.reporting').slick({
            dots: false,
            arrows: true,
            infinite: true,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>