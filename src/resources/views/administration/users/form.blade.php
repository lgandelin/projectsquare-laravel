<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="first_name">{{ __('projectsquare::users.first_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.first_name') }}" name="first_name" @if (isset($user_first_name))value="{{ $user_first_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="last_name">{{ __('projectsquare::users.last_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.last_name') }}" name="last_name" @if (isset($user_last_name))value="{{ $user_last_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="email">{{ __('projectsquare::users.email') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.email') }}" name="email" @if (isset($user_email))value="{{ $user_email }}"@endif autocomplete="off" />
    </div>

    @if ($password_field)
        <div class="form-group">
            <label for="password">{{ __('projectsquare::users.password') }}</label><br/>
            <input class="form-control" type="password" placeholder="{{ __('projectsquare::users.password') }}" name="password" autocomplete="off" />
        </div>

        <div class="form-group">
            <label for="password_confirmation">{{ __('projectsquare::my.password_confirmation') }}</label><br/>
            <input class="form-control" type="password" placeholder="{{ __('projectsquare::my.password_confirmation') }}" name="password_confirmation" autocomplete="off" />
        </div>
    @else
        <div class="form-group">
            <a href="{{ route('users_generate_password', ['id' => $user_id]) }}">
                <span class="btn btn-primary button">
                    <span class="glyphicon glyphicon-repeat"></span>
                    {{ __('projectsquare::users.generate_password') }}
                </span></a>

            @include('projectsquare::includes.tooltip', [
                'text' => __('projectsquare::users.generate_password_notice')
            ])
        </div>
    @endif

    <div class="form-group">
        <label for="role">{{ __('projectsquare::users.profile') }}</label><br/>
        <select class="form-control" name="role_id" id="role_id" required>
            <option value="">{{ __('projectsquare::generic.choose_value') }}</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @if (isset($user_role_id) && $user_role_id == $role->id)selected="selected" @endif>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="is_administrator">{{ __('projectsquare::users.is_administrator') }}</label><br/>
        Oui <input type="radio" placeholder="{{ __('projectsquare::users.is_administrator') }}" name="is_administrator" value="y" @if (isset($is_administrator) && $is_administrator) checked @endif autocomplete="off" />
        Non <input type="radio" placeholder="{{ __('projectsquare::users.is_administrator') }}" name="is_administrator" value="n" @if (isset($is_administrator) && !$is_administrator) checked @endif @if (!isset($is_administrator)) checked @endif autocomplete="off" />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    {!! csrf_field() !!}
</form>