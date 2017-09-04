@if ($current_projects_reporting)
    <div class="reporting">
        @foreach ($current_projects_reporting as $project)
            <div class="project" style="background: {{ $project->color }};">
                {{ $project->name }}

                <span class="time-values">
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
            </div>
        @endforeach
    </div>
@endif
