<h2>{{ __('projectsquare::clients.information') }}</h2><br/>

<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ __('projectsquare::clients.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ __('projectsquare::clients.name_placeholder') }}" name="name" @if (isset($client_name))value="{{ $client_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ __('projectsquare::clients.address') }}</label>
        <textarea class="form-control" rows="5" class="col-md-6" placeholder="{{ __('projectsquare::clients.address') }}" name="address">@if (isset($client_address)){{ $client_address }}@endif</textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::generic.valid') }}
        </button>
    </div>

    @if (isset($client_id))
        <input type="hidden" name="client_id" value="{{ $client_id }}" />
    @endif

    {!! csrf_field() !!}
</form>