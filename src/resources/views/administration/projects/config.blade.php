<div class="page-header">
    <h1>{{ __('projectsquare::projects.config') }}</h1>
    <a href="{{ route('projects_index') }}" class="btn back"></a>
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

<form action="{{ route('projects_update_config') }}" method="post">
    <h3>{{ __('projectsquare::project.urls') }}</h3>

    <div class="form-group">
        <label for="name">{{ __('projectsquare::projects.website_front_url') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.front_url')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::projects.website_front_url') }}" name="website_front_url" @if (isset($project_website_front_url))value="{{ $project_website_front_url }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ __('projectsquare::projects.website_back_url') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.cms_url')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::projects.website_back_url') }}" name="website_back_url" @if (isset($project_website_back_url))value="{{ $project_website_back_url }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    <h3>{{ __('projectsquare::project.settings') }}</h3>

    <div class="form-group">
        <label for="value">
            {{ __('projectsquare::project.acceptable_loading_time') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.acceptable_loading_time')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::project.acceptable_loading_time_placeholder') }}" name="ACCEPTABLE_LOADING_TIME" @if (isset($acceptable_loading_time))value="{{ $acceptable_loading_time }}"@endif />
    </div>

    <div class="form-group">
        <label for="value">
            {{ __('projectsquare::project.alert_loading_time_email') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.email_loading_time')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::project.alert_loading_time_email') }}" name="ALERT_LOADING_TIME_EMAIL" @if (isset($alert_loading_time_email))value="{{ $alert_loading_time_email }}"@endif />
    </div>

    <div class="form-group">
        <label for="value">
            {{ __('projectsquare::project.slack_channel') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.slack_channel')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::project.slack_channel_placeholder') }}" name="SLACK_CHANNEL" @if (isset($slack_channel))value="{{ $slack_channel }}"@endif />
    </div>

    <div class="form-group">
        <label for="value">
            {{ __('projectsquare::project.ga_view_id') }}
            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::tooltips.project.profile_google_analytics')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::project.ga_view_id_placeholder') }}" name="GA_VIEW_ID" @if (isset($ga_view_id))value="{{ $ga_view_id }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($project_id))
        <input type="hidden" name="project_id" value="{{ $project_id }}" />
    @endif

    {!! csrf_field() !!}
</form>