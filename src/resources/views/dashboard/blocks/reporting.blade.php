@if ($current_projects_reporting)
    <div class="reporting">
        @foreach ($current_projects_reporting as $project)
            <div class="project" style="background: {{ $project->color }};">
                {{ $project->name }}
                @if ($project->progress){{ $project->progress }}%@endif
                @if ($project->differenceSpentEstimated > 0)+@elseif ($project->differenceSpentEstimated < 0)@endif {{ $project->differenceSpentEstimated }}j
            </div>
        @endforeach
    </div>
@endif
