@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar', ['active' => 'settings'])

    <div class="settings-template">
        <h1 class="page-header">{{ trans('gateway::project.settings') }}</h1>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="name">{{ trans('gateway::settings.ping_request_frequency') }}</label>
                <select class="form-control" name="ping_request_frequency">
                    <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                    <option value="1">Toutes les minutes</option>
                    <option value="2">Toutes les 5 minutes</option>
                    <option value="3">Toutes les 15 minutes</option>
                    <option value="4">Toutes les 30 minutes</option>
                    <option value="5">Toutes les heures</option>
                    <option value="6">Toutes les 4 heures</option>
                    <option value="7">Tous les jours</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('gateway::generic.valid') }}
                </button>

                <a href="{{ route('projects_index') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('gateway::generic.back') }}</a>
            </div>
        </form>

    </div>
@endsection