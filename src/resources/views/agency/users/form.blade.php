<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="first_name">{{ trans('gateway::users.first_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::users.first_name') }}" name="first_name" @if (isset($user_first_name))value="{{ $user_first_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="last_name">{{ trans('gateway::users.last_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::users.last_name') }}" name="last_name" @if (isset($user_last_name))value="{{ $user_last_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="email">{{ trans('gateway::users.email') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::users.email') }}" name="email" @if (isset($user_email))value="{{ $user_email }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('gateway::users.client') }}</label>
        @if (isset($clients))
            <select class="form-control" name="client_id">
                <option value="">{{ trans('gateway::generic.choose_value') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if (isset($user) && $user->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-success" value="{{ trans('gateway::generic.valid') }}" />
        <a href="{{ route('users_index') }}" class="btn btn-default">{{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    {!! csrf_field() !!}
</form>