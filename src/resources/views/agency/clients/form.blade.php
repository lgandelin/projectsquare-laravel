<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('gateway::clients.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('gateway::clients.name_placeholder') }}" name="name" @if (isset($client_name))value="{{ $client_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('gateway::generic.valid') }}
        </button>
        <a href="{{ route('clients_index') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i>{{ trans('gateway::generic.back') }}</a>
    </div>

    @if (isset($client_id))
        <input type="hidden" name="client_id" value="{{ $client_id }}" />
    @endif

    {!! csrf_field() !!}
</form>