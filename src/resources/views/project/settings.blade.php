@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'settings'])

    <div class="settings-template">
        <h1 class="page-header">{{ trans('projectsquare::project.settings') }}</h1>

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

        <h3>{{ trans('projectsquare::project.monitoring') }}</h3>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">{{ trans('projectsquare::settings.acceptable_loading_time') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::settings.acceptable_loading_time_placeholder') }}" name="value" @if (isset($acceptable_loading_time))value="{{ $acceptable_loading_time }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="ACCEPTABLE_LOADING_TIME" />

            {!! csrf_field() !!}
        </form>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">{{ trans('projectsquare::settings.alert_loading_time_email') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::settings.alert_loading_time_email') }}" name="value" @if (isset($alert_loading_time_email))value="{{ $alert_loading_time_email }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="ALERT_LOADING_TIME_EMAIL" />

            {!! csrf_field() !!}
        </form>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">{{ trans('projectsquare::settings.slack_channel') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::settings.slack_channel_placeholder') }}" name="value" @if (isset($slack_channel))value="{{ $slack_channel }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
            <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="SLACK_CHANNEL" />

            {!! csrf_field() !!}
        </form>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">{{ trans('projectsquare::settings.ga_view_id') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::settings.ga_view_id_placeholder') }}" name="value" @if (isset($ga_view_id))value="{{ $ga_view_id }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="GA_VIEW_ID" />

            {!! csrf_field() !!}
        </form>
    </div>
@endsection