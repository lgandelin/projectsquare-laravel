<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::projects.name_placeholder') }}" name="name" @if (isset($project_name))value="{{ $project_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.client') }}</label>
        @if (isset($clients))
            <select class="form-control" name="client_id">
                <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if (isset($project) && $project->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>
        @else
            <div class="info bg-info">{{ trans('projectsquare::no_client_yet') }}</div>
        @endif
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.website_front_url') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::projects.website_front_url') }}" name="website_front_url" @if (isset($project_website_front_url))value="{{ $project_website_front_url }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.website_back_url') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::projects.website_back_url') }}" name="website_back_url" @if (isset($project_website_back_url))value="{{ $project_website_back_url }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.status') }}</label>
        <select class="form-control" name="status">
            <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
            @for ($i = 1; $i <= 3; $i++)
                <option value="{{ $i }}" @if (isset($project) && $project->status == $i)selected="selected"@endif>{{ trans('projectsquare::projects.status_' . $i) }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::projects.color') }}</label>
        <input type="text" name="color" class="form-control colorpicker" data-control="saturation" placeholder="{{ trans('projectsquare::projects.color') }}"  @if (isset($project_color))value="{{ $project_color }}"@endif size="7">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">
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
    <p>&nbsp;</p>
    <h3>{{ trans('projectsquare::projects.project_resources') }}</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('projectsquare::users.user') }}</th>
                <th>{{ trans('projectsquare::roles.role') }}</th>
                <th>{{ trans('projectsquare::generic.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($project->users as $user)
                <tr>
                    <td>{{ $user->complete_name }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>
                        <a href="{{ route('projects_delete_user', ['project_id' => $project_id, 'user_id' => $user->id]) }}" class="btn cancel btn-delete">
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>{{ trans('projectsquare::projects.add_resource') }}</h3>
    <form action="{{ route('projects_add_user') }}" method="post">
        <div class="row">
            <div class="col-md-3">
                <label for="user_id">{{ trans('projectsquare::users.user') }}</label>
                @if (isset($users))
                    <select class="form-control" name="user_id">
                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->complete_name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-md-3">
                <label for="role_id">{{ trans('projectsquare::roles.role') }}</label>
                @if (isset($roles))
                    <select class="form-control" name="role_id">
                        <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="info bg-info">{{ trans('projectsquare::no_role_yet') }}</div>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 1.5rem">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>

        <input type="hidden" name="project_id" value="{{ $project_id }}" />

        {!! csrf_field() !!}
    </form>

    <div class="settings-template" style="margin-top: 5rem">
        <h3>{{ trans('projectsquare::project.settings') }}</h3>

        <form action="{{ route('project_settings', ['id' => $project->id]) }}" method="post">
            <div class="form-group">
                <label for="value">{{ trans('projectsquare::settings.acceptable_loading_time') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::settings.acceptable_loading_time_placeholder') }}" name="value" @if (isset($acceptable_loading_time))value="{{ $acceptable_loading_time }}"@endif />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
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

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
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

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
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

                <a href="{{ route('project_index', ['id' => $project->id]) }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            @if (isset($project->id))
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
            @endif

            <input type="hidden" name="key" value="GA_VIEW_ID" />

            {!! csrf_field() !!}
        </form>
    </div>
@endif

@section('scripts')
    <script>
        $('.colorpicker').minicolors({
            theme: 'bootstrap'
        });
    </script>
@endsection