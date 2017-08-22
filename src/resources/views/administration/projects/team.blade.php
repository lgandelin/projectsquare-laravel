<div class="middle-column">
    @foreach ($users as $role)
        <div class="parent">
            <div class="parent-wrapper">
                <span class="name">{{ $role->name }}</span>
                <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
            </div>
            <div class="childs">
                @if ($role->users)
                    @foreach ($role->users as $user)
                        <div class="child">
                            <div class="child-wrapper">
                                @include('projectsquare::includes.avatar', [
                                    'id' => $user->id,
                                    'name' => $user->complete_name
                                ])

                                <span class="name">{{ $user->complete_name }}</span>
                                <span class="fa fa-plus-circle add-user"></span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="project-team-content">

    <div class="page-header">
        <h1>{{ trans('projectsquare::projects.team') }}</h1>
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

    <div class="project-team">
        <h3>{{ trans('projectsquare::projects.project_resources') }}
            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::tooltips.project.ressources')
            ])
        </h3>
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

        <!--<h3>{{ trans('projectsquare::projects.add_resource') }}</h3>
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
            </div>

            <button type="submit" class="btn valid" style="margin-top: 2.5rem">
                <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
            </button>

            <input type="hidden" name="project_id" value="{{ $project_id }}" />

            {!! csrf_field() !!}
        </form>-->
    </div>
</div>