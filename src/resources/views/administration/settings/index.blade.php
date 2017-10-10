@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::settings.index') }}</h1>
            </div>

            @if (isset($error))
                <div class="info bg-danger">
                    {{ $error }}
                </div>
            @endif

            @if (isset($confirmation))
                <div class="info bg-success">
                    {{ $confirmation }}
                </div>
            @endif

            <form action="{{ route('settings_update') }}" method="post">
                <div class="form-group">
                    <label for="value">
                        {{ trans('projectsquare::settings.slack') }}
                        @include('projectsquare::includes.tooltip', [
                            'text' => trans('projectsquare::tooltips.slack')
                        ])
                    </label>
                    <input class="form-control" type="text" placeholder="{{ trans('projectsquare::settings.slack_placeholder') }}" name="value" @if (isset($slack))value="{{ $slack }}"@endif />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn valid">
                        <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                    </button>
                </div>

                <input type="hidden" name="key" value="SLACK_URL" />

                {!! csrf_field() !!}
            </form>

            <div class="form-group">
                <label for="value" style="display: block;">
                    {{ trans('projectsquare::settings.reset_platform') }}
                    @include('projectsquare::includes.tooltip', [
                        'text' => trans('projectsquare::tooltips.reset_platform')
                    ])
                </label>
                <a class="btn delete btn-delete" href="{{ route('reset_platform') }}"><i class="glyphicon glyphicon-remove picto-delete"></i> {{ trans('projectsquare::settings.reset') }}</a>
            </div>

        </div>
    </div>
@endsection