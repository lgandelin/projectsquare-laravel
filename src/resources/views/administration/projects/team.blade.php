<div class="middle-column">
    <div class="filter-user">
        <input autocomplete="off" class="search-input" type="text" placeholder="{{ trans('projectsquare::generic.search') }} ..." />
        <i class="glyphicon glyphicon-search search-icon"></i>
    </div>

    @foreach ($users as $role)
        <div class="parent">
            <div class="parent-wrapper">
                <span class="name">{{ $role->name }}</span>
                <span class="glyphicon glyphicon-triangle-top toggle-childs"></span>
            </div>
            <div class="childs">
                @if ($role->users)
                    @foreach ($role->users as $user)
                        <div class="child" data-id="{{ $user->id }}" data-role="@if ($user->role){{ $user->role->name }}@endif" data-name="{{ $user->complete_name }}">
                            <div class="child-wrapper">
                                @include('projectsquare::includes.avatar', [
                                    'id' => $user->id,
                                    'name' => $user->complete_name
                                ])

                                <span class="name">{{ $user->complete_name }}</span>
                                <span @if (in_array($user->id, $userIDs))style="display:none" @endif class="fa fa-plus-circle btn-add-user"></span>
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
                <tr data-id="{{ $user->id }}">
                    <td>{{ $user->complete_name }}</td>
                    <td>@if ($user->role){{ $user->role->name }}@endif</td>
                    <td align="right">
                        <span class="btn cancel btn-delete-user"></span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form action="{{ route('projects_update_team') }}" method="post">
            <button type="submit" class="btn valid-team">
                <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
            </button>
            <input type="hidden" id="user_ids" name="user_ids" value="{{ json_encode($userIDs) }}" />
            <input type="hidden" name="project_id" value="{{ $project->id }}" />
            {{ csrf_field() }}
        </form>
    </div>
</div>

<script id="user-template" type="text/x-handlebars-template">
    <tr data-id="@{{ id }}">
        <td>@{{ name  }}</td>
        <td>@{{ role }}</td>
        <td align="right">
            <span class="btn cancel btn-delete-user"></span>
        </td>
    </tr>
</script>

@section('scripts')
    @parent
    <script src="{{ asset('js/administration/project-team.js') }}"></script>
@endsection