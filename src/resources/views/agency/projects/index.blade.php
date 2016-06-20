@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::projects.projects_list') }}</li>
    </ol>-->
  <div class="templates">
    <div class="page-header">
        <h1>{{ trans('projectsquare::projects.projects_list') }}</h1>
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
                <th>#</th>
                <th>{{ trans('projectsquare::projects.client') }}</th>
                <th>{{ trans('projectsquare::projects.project') }}</th>
                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
            </thead>

            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>@if (isset($project->client)){{ $project->client->name }}@endif</td>
                        <td>{{ $project->name }}</td>
                        <td align="right">
                            <a href="{{ route('projects_edit', ['id' => $project->id]) }}" class="btn see-more"></a>
                            <a href="{{ route('projects_delete', ['id' => $project->id]) }}" class="btn cancel"></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {!! $projects->render() !!}
        </div>
    </div>
@endsection