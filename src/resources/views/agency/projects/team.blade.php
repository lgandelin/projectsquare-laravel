<div class="page-header">
    <h1>{{ trans('projectsquare::projects.team') }}</h1>
</div>

<div class="project-team">
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
                <td>@if ($user->role){{ $user->role->name }}@endif</td>
                <td align="right">
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

        <button type="submit" class="btn valid" style="margin-top: 2.5rem">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>

        <input type="hidden" name="project_id" value="{{ $project_id }}" />

        {!! csrf_field() !!}
    </form>
</div>