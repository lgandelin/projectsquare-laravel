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
        <label for="password">{{ trans('projectsquare::users.password') }}</label><br/>
        @if ($password_field)
            <input class="form-control" type="password" placeholder="@if (isset($user_id)){{ trans('projectsquare::users.password_leave_empty') }}@else{{ trans('projectsquare::users.password') }}@endif" name="password" autocomplete="off" />
        @else
            <a href="{{ route('users_generate_password', ['id' => $user_id]) }}">
                <span class="btn btn-primary">
                    <span class="glyphicon glyphicon-repeat"></span>
                    {{ trans('projectsquare::users.generate_password') }}
                </span>
            </a>
            <br/>
            <span style="font-style: italic; display: inline-block;margin-top: 5px;">{{ trans('projectsquare::users.generate_password_notice') }}</span>
        @endif
    </div>

    <div class="form-group">
        <label for="client_id">{{ trans('projectsquare::users.client') }}</label>
        @if (isset($clients))
            <select class="form-control" name="client_id">
                <option value="">{{ trans('projectsquare::generic.choose_value') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if (isset($user) && $user->clientID == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="form-group">
        <label for="is_administrator">{{ trans('projectsquare::users.is_administrator') }}</label><br/>
        Oui <input type="radio" placeholder="{{ trans('projectsquare::users.is_administrator') }}" name="is_administrator" value="y" @if (isset($is_administrator) && $is_administrator) checked @endif autocomplete="off" />
        Non <input type="radio" placeholder="{{ trans('projectsquare::users.is_administrator') }}" name="is_administrator" value="n" @if (isset($is_administrator) && !$is_administrator) checked @endif autocomplete="off" />
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