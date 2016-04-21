@extends('projectsquare::app')

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-md-12 top-bar">
            <div class="pull-left">
                <h1 class="logo"><a href="{{ route('dashboard') }}">projectsquare</a></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('projectsquare::login.panel_title') }}</div>
                <div class="panel-body">

                    @if (isset($error))
                        <div class="info bg-danger">
                            {{ $error }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login_handler') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Adresse email</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" autocomplete="off" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Mot de passe</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" autocomplete="off" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember_token" />Se souvenir de moi
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Se connecter <span class="glyphicon glyphicon-log-in" style="margin-left: 1rem"></span>
                                </button>

                                <a class="btn btn-link" href="{{ route('forgotten_password') }}">Mot de passe oublié ?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
