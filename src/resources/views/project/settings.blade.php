@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar', ['active' => 'settings'])

    <div class="settings-template">
        <h1 class="page-header">{{ trans('gateway::project.settings') }}</h1>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('gateway::generic.valid') }}
                </button>

                <a href="{{ route('projects_index') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('gateway::generic.back') }}</a>
            </div>
        </form>
    </div>
@endsection