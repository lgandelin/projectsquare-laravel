@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::projects.projects_list') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::projects.projects_list') }}</h1>
    </div>

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
                    <td>
                        <a href="{{ route('projects_edit', ['id' => $project->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ trans('projectsquare::generic.edit') }}</a>
                        <a href="{{ route('projects_delete', ['id' => $project->id]) }}" class="btn btn-danger btn-delete"><span class="glyphicon glyphicon-remove"></span> {{ trans('projectsquare::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $projects->render() !!}
    </div>

    <a href="{{ route('projects_add') }}" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('projectsquare::projects.add_project') }}</a>
@endsection