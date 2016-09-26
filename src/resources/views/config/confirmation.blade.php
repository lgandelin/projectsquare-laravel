@extends('projectsquare::app')

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 top-bar">
                <div class="pull-left">
                    <h1 class="logo">{{ trans('projectsquare::install.installation') }}</h1>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 5rem;">
            <div class="col-lg-6 col-lg-offset-3">
                <h3>{{ trans('projectsquare::install.program_installed') }}</h3>
                <p>{{ trans('projectsquare::install.installation_text_1') }}<br/><br/>
                    {{ trans('projectsquare::install.installation_text_2') }} <a href="{{ route('dashboard') }}"><span class="btn btn-primary">{{ trans('projectsquare::install.click') }}</span></a>
                </p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                window.location.href = "{{ route('dashboard') }}";
            }, 10000);
        });
    </script>
@endsection