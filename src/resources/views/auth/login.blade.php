@extends('projectsquare::app')

@section('main-content')
<div class="container">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="margin-top: 5rem">
                <div class="panel-heading">{{ trans('projectsquare::login.panel_title') }}</div>
                <div class="panel-body">

                    @if (isset($error))
                        <div class="info bg-danger">
                            {{ $error }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login_handler') }}">

                        <h1 class="logo" style="text-align: center; margin-top: 1rem; margin-bottom: 4rem"><a href="{{ route('dashboard') }}"><img src="{{asset('img/top-bar/logo.png')}}"></a></h1>

                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('projectsquare::login.email') }}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('projectsquare::login.password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" autocomplete="off" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember_token" />{{ trans('projectsquare::login.remember') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn valid">
                                    {{ trans('projectsquare::login.login') }} <i class="fa fa-btn fa-sign-in" style="margin-left: 1rem;"></i></span>
                                </button>

                                <a class="btn btn-link" href="{{ route('forgotten_password') }}">{{ trans('projectsquare::login.forgotten_password') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
