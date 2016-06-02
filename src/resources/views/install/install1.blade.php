@extends('projectsquare::app')

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 top-bar">
                <div class="pull-left">
                    <h1 class="logo">Installation</h1>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 5rem;">
            <div class="col-lg-6 col-lg-offset-3">
                <form action="{{ route('install1_handler') }}" method="post">

                    <h3>Compte administrateur</h3>
                    <hr>

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
                        <button type="submit" class="btn btn-success pull-right">
                            {{ trans('projectsquare::generic.next') }} <i class="glyphicon glyphicon glyphicon-arrow-right"></i>
                        </button>
                    </div>

                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>
@endsection