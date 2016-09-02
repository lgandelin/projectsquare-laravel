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

    @if ($password_field)
        <div class="form-group">
            <label for="password">{{ trans('projectsquare::users.password') }}</label><br/>
            <input class="form-control" type="password" placeholder="{{ trans('projectsquare::users.password') }}" name="password" autocomplete="off" />
        </div>

        <div class="form-group">
            <label for="password_confirmation">{{ trans('projectsquare::my.password_confirmation') }}</label><br/>
            <input class="form-control" type="password" placeholder="{{ trans('projectsquare::my.password_confirmation') }}" name="password_confirmation" autocomplete="off" />
        </div>
    @else
        <div class="form-group">
            <a href="{{ route('users_generate_password', ['id' => $user_id]) }}">
                <span class="btn btn-primary button">
                    <span class="glyphicon glyphicon-repeat"></span>
                    {{ trans('projectsquare::users.generate_password') }}
                </span></a>

            @include('projectsquare::includes.tooltip', [
                'text' => trans('projectsquare::users.generate_password_notice')
            ])
        </div>
    @endif

    <div class="form-group">
        <label for="is_administrator">{{ trans('projectsquare::users.is_administrator') }}</label><br/>
        Oui <input type="radio" placeholder="{{ trans('projectsquare::users.is_administrator') }}" name="is_administrator" value="y" @if (isset($is_administrator) && $is_administrator) checked @endif autocomplete="off" />
        Non <input type="radio" placeholder="{{ trans('projectsquare::users.is_administrator') }}" name="is_administrator" value="n" @if (isset($is_administrator) && !$is_administrator) checked @endif @if (!isset($is_administrator)) checked @endif autocomplete="off" />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
        <a href="{{ \URL::previous() }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    {!! csrf_field() !!}
</form>