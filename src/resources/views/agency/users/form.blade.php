<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="first_name">{{ trans('projectsquare::users.first_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.first_name') }}" name="first_name" @if (isset($user_first_name))value="{{ $user_first_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="last_name">{{ trans('projectsquare::users.last_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.last_name') }}" name="last_name" @if (isset($user_last_name))value="{{ $user_last_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="email">{{ trans('projectsquare::users.email') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.email') }}" name="email" @if (isset($user_email))value="{{ $user_email }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="password">{{ trans('projectsquare::users.password') }}</label>
        <input class="form-control" type="password" placeholder="@if (isset($user_id)){{ trans('projectsquare::users.password_leave_empty') }}@else{{ trans('projectsquare::users.password') }}@endif" name="password" autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::users.client') }}</label>
        @if (isset($clients))
            <select class="form-control" name="client_id">
                <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if (isset($user) && $user->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
        <a href="{{ route('users_index') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    {!! csrf_field() !!}
</form>