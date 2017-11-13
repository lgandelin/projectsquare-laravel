<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ __('projectsquare::roles.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::roles.name_placeholder') }}" name="name" @if (isset($role_name))value="{{ $role_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($role_id))
        <input type="hidden" name="role_id" value="{{ $role_id }}" />
    @endif

    {!! csrf_field() !!}
</form>