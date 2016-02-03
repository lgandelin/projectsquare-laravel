<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('gateway::roles.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::roles.name_placeholder') }}" name="name" @if (isset($role_name))value="{{ $role_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('gateway::generic.valid') }}
        </button>
        <a href="{{ route('roles_index') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($role_id))
        <input type="hidden" name="role_id" value="{{ $role_id }}" />
    @endif

    {!! csrf_field() !!}
</form>