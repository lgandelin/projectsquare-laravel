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
                    <th>{{ trans('projectsquare::projects.project') }}<a href="{{ route('projects_index', ['sc' => 'name', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-alpha-{{ $sort_order }}"></i></a></th>
                    <th>{{ trans('projectsquare::projects.client') }}<a href="{{ route('projects_index', ['sc' => 'client_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                    <th>{{ trans('projectsquare::projects.status') }}<a href="{{ route('projects_index', ['sc' => 'status_id', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort"></i></a></th>
                    <th>{{ trans('projectsquare::projects.creation_date') }}<a href="{{ route('projects_index', ['sc' => 'created_at', 'so' => $sort_order, 'it' => $items_per_page]) }}" class="sort-icon"><i class="fa fa-sort-{{ $sort_order }}"></i></a></th>
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
                            <td>
                                <a href="{{ route('projects_edit', ['id' => $project->id]) }}">
                                    @if ($project->created_at)
                                        {{ DateTime::createFromFormat('Y-m-d H:i:s', $project->created_at)->format('d/m/Y') }}
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
                @include('projectsquare::administration.includes.items_per_page')
                {{ $projects->appends(['it' => $items_per_page, 'sc' => $sort_column, 'so' => $sort_order])->links() }}
            </div>
        </div>
    </div>
@endsection