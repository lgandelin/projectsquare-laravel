<h2>{{ trans('projectsquare::clients.information') }}</h2><br/>

<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('projectsquare::clients.label') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::clients.name_placeholder') }}" name="name" @if (isset($client_name))value="{{ $client_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="name">{{ trans('projectsquare::clients.address') }}</label>
        <textarea class="form-control" rows="5" class="col-md-6" placeholder="{{ trans('projectsquare::clients.address') }}" name="address">@if (isset($client_address)){{ $client_address }}@endif</textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
        </button>
        <a href="{{ \URL::previous() }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i>{{ trans('projectsquare::generic.back') }}</a>
    </div>

    @if (isset($client_id))
        <input type="hidden" name="client_id" value="{{ $client_id }}" />
    @endif

    {!! csrf_field() !!}
</form>