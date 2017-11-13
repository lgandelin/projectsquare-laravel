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
            <input class="form-control" type="password" placeholder="@if (isset($user_id)){{ __('projectsquare::users.password_leave_empty') }}@else{{ __('projectsquare::users.password') }}@endif" name="password" autocomplete="off" />
        </div>

        <div class="form-group">
            <label for="password_confirmation">{{ __('projectsquare::my.password_confirmation') }}</label><br/>
            <input class="form-control" type="password" placeholder="@if (isset($user->id)){{ __('projectsquare::users.password_leave_empty') }}@else{{ __('projectsquare::my.password_confirmation') }}@endif" name="password_confirmation" autocomplete="off" />
        </div>
    @else
        <a href="{{ route('users_generate_password', ['id' => $user_id]) }}">
            <span class="btn button">
                <span class="glyphicon glyphicon-repeat"></span>
                {{ __('projectsquare::users.generate_password') }}
            </span>
        </a>
        <br/>
        <span style="font-style: italic; display: inline-block;margin-top: 5px;">{{ __('projectsquare::clients.generate_password_notice') }}</span>
    @endif

    <div class="form-group">
        <label for="mobile">{{ __('projectsquare::users.mobile') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.mobile') }}" name="mobile" @if (isset($user_mobile))value="{{ $user_mobile }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="phone">{{ __('projectsquare::users.phone') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.phone') }}" name="phone" @if (isset($user_phone))value="{{ $user_phone }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="client_role">{{ __('projectsquare::users.role') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::users.role') }}" name="client_role" @if (isset($user_role))value="{{ $user_role }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    <input type="hidden" name="client_id" value="{{ $client_id }}" />

    {!! csrf_field() !!}
</form>