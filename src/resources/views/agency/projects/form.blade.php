<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('gateway::projects.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::projects.name_placeholder') }}" name="name" @if (isset($project_name))value="{{ $project_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('gateway::projects.client') }}</label>
        @if (isset($clients))
            <select class="form-control" name="client_id">
                <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if (isset($project) && $project->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>
        @else
            <div class="info bg-info">{{ trans('gateway::no_client_yet') }}</div>
        @endif
    </div>

    <div class="form-group">
        <label for="name">{{ trans('gateway::projects.site_url') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::projects.site_url') }}" name="site_url" @if (isset($project_site_url))value="{{ $project_site_url }}"@endif />
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
        <a href="{{ route('projects_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($project_id))
        <input type="hidden" name="project_id" value="{{ $project_id }}" />
    @endif

    {!! csrf_field() !!}
</form>

@if (isset($project_id))
    <p>&nbsp;</p>
    <h3>{{ trans('gateway::projects.project_resources') }}</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('gateway::users.user') }}</th>
                <th>{{ trans('gateway::roles.role') }}</th>
                <th>{{ trans('gateway::generic.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($project->users as $user)
                <tr>
                    <td>{{ $user->complete_name }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td><a href="{{ route('projects_delete_user', ['project_id' => $project_id, 'user_id' => $user->id]) }}" class="btn btn-danger">{{ trans('gateway::generic.delete') }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>{{ trans('gateway::projects.add_resource') }}</h3>
    <form action="{{ route('projects_add_user') }}" method="post">
        <div class="row">
            <div class="col-md-3">
                <label for="user_id">{{ trans('gateway::users.user') }}</label>
                @if (isset($users))
                    <select class="form-control" name="user_id">
                        <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->complete_name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-md-3">
                <label for="role_id">{{ trans('gateway::roles.role') }}</label>
                @if (isset($roles))
                    <select class="form-control" name="role_id">
                        <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="info bg-info">{{ trans('gateway::no_role_yet') }}</div>
                @endif
            </div>

            <div class="col-md-3">
                <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.add') }}" style="margin-top: 2.5rem"/>
            </div>
        </div>

        <input type="hidden" name="project_id" value="{{ $project_id }}" />

        {!! csrf_field() !!}
    </form>
@endif