@extends('projectsquare::default')

@section('content')
    <div class="content-page">
      <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::projects.projects_list') }}
                @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.projects_list')
                  ])
            </h1>
        </div>
              <a href="{{ route('projects_add') }}" class="btn pull-right add"></a>
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

            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('projectsquare::projects.project') }}</th>
                    <th>{{ trans('projectsquare::projects.client') }}</th>
                    <th>{{ trans('projectsquare::projects.status') }}</th>
                    <th>{{ trans('projectsquare::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td class="project-border" style="border-left: 5px solid {{ $project->color }}"></td>
                            <td>
                                <a href="{{ route('projects_edit', ['id' => $project->id]) }}">
                                    {{ $project->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('projects_edit', ['id' => $project->id]) }}">
                                    @if (isset($project->client)){{ $project->client->name }}@endif
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('projects_edit', ['id' => $project->id]) }}">
                                    @if ($project->status_id == Webaccess\ProjectSquare\Entities\Project::IN_PROGRESS)En cours
                                    @elseif ($project->status_id == Webaccess\ProjectSquare\Entities\Project::ARCHIVED)Archivé
                                    @endif
                                </a>
                            </td>
                            <td width="5%" class="action" align="right">
                                <a href="{{ route('projects_edit', ['id' => $project->id]) }}">
                                    <i class="btn see-more"></i>
                                </a>
                                <a href="{{ route('projects_delete', ['id' => $project->id]) }}">
                                    <i class="btn cancel btn-delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $projects->render() !!}
            </div>
        </div>
    </div>
@endsection