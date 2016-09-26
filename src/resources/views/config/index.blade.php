@extends('projectsquare::app')

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <img src="{{ asset('img/install/logo-big.png') }}" width="268" height="160" style="display: block; margin: auto; margin-top: 3rem;"/>
            </div>
        </div>
        <div class="templates install-template" style="margin-top: 4rem; padding-bottom: 5rem;">
            <div class="page-header row">
                <div class="col-md-12 top-bar">
                    <div class="pull-left">
                        <h1 class="logo">{{ trans('projectsquare::config.configuration') }}</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h3 style="margin-top: 1rem;">{{ trans('projectsquare::config.welcome_title') }}</h3>

                    <p>
                        {{ trans('projectsquare::config.welcome_text') }}
                    </p>
                </div>
            </div>
            <div class="row" style="margin-top: 4rem;">
                <div class="col-lg-6 col-lg-offset-3">
                    <form action="{{ route('config_handler') }}" method="post">

                        <div class="form-group">
                            <label for="first_name">{{ trans('projectsquare::users.first_name') }}</label>
                            <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.first_name') }}" name="first_name" />
                        </div>

                        <div class="form-group">
                            <label for="last_name">{{ trans('projectsquare::users.last_name') }}</label>
                            <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.last_name') }}" name="last_name" />
                        </div>

                        <div class="form-group">
                            <label for="email">{{ trans('projectsquare::users.email') }}</label>
                            <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.email') }}" name="email" />
                        </div>

                        <div class="form-group">
                            <label for="last_name">{{ trans('projectsquare::users.password') }}</label>
                            <input class="form-control" type="password" placeholder="{{ trans('projectsquare::users.password') }}" name="password" />
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn valid pull-right">
                                <i class="glyphicon glyphicon-ok"></i>
                                {{ trans('projectsquare::generic.valid') }}
                            </button>
                        </div>

                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection