<form action="{{ route('projects_update_config') }}" method="post">
    <h3>{{ trans('projectsquare::project.urls') }}</h3>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.website_front_url') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.front_url')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::projects.website_front_url') }}" name="website_front_url" @if (isset($project_website_front_url))value="{{ $project_website_front_url }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.website_back_url') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.cms_url')
            ])
        </label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::projects.website_back_url') }}" name="website_back_url" @if (isset($project_website_back_url))value="{{ $project_website_back_url }}"@endif />
    </div>

    <h3>{{ trans('projectsquare::project.scheduled_times') }}</h3>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.tasks_scheduled_time') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.scheduled_time_reporting')
            ])
        </label>
        <input type="text" name="tasks_scheduled_time" class="form-control" placeholder="{{ trans('projectsquare::projects.scheduled_time_placeholder') }}"  @if (isset($project_tasks_scheduled_time))value="{{ $project_tasks_scheduled_time }}"@endif size="7">
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.tickets_scheduled_time') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.scheduled_time_reporting')
            ])
        </label>
        <input type="text" name="tickets_scheduled_time" class="form-control" placeholder="{{ trans('projectsquare::projects.scheduled_time_placeholder') }}"  @if (isset($project_tickets_scheduled_time))value="{{ $project_tickets_scheduled_time }}"@endif size="7">
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>

        <a href="{{ route('projects_index') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
    </div>

    @if (isset($project_id))
        <input type="hidden" name="project_id" value="{{ $project_id }}" />
    @endif

    {!! csrf_field() !!}
</form>

@if (isset($project_id))

    <div class="settings-template" >
        <h3>{{ trans('projectsquare::project.settings') }}</h3>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">
                    {{ trans('projectsquare::project.acceptable_loading_time') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.project.acceptable_loading_time')
                    ])
                </label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::project.acceptable_loading_time_placeholder') }}" name="value" @if (isset($acceptable_loading_time))value="{{ $acceptable_loading_time }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn valid">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('projects_index') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="ACCEPTABLE_LOADING_TIME" />

            {!! csrf_field() !!}
        </form>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">
                    {{ trans('projectsquare::project.alert_loading_time_email') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.project.email_loading_time')
                    ])
                </label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::project.alert_loading_time_email') }}" name="value" @if (isset($alert_loading_time_email))value="{{ $alert_loading_time_email }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn valid">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('projects_index') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="ALERT_LOADING_TIME_EMAIL" />

            {!! csrf_field() !!}
        </form>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">
                    {{ trans('projectsquare::project.slack_channel') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.project.slack_channel')
                    ])
                </label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::project.slack_channel_placeholder') }}" name="value" @if (isset($slack_channel))value="{{ $slack_channel }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn valid">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('projects_index') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="SLACK_CHANNEL" />

            {!! csrf_field() !!}
        </form>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">
                    {{ trans('projectsquare::project.ga_view_id') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.project.profile_google_analytics')
                    ])
                </label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::project.ga_view_id_placeholder') }}" name="value" @if (isset($ga_view_id))value="{{ $ga_view_id }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn valid">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('projects_index') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="GA_VIEW_ID" />

            {!! csrf_field() !!}
        </form>
    </div>
@endif