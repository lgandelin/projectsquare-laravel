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
                <form action="{{ route('install2_handler') }}" method="post">

                    <h3>Agence</h3>
                    <hr>

                    <div class="form-group">
                        <label for="agency_name">{{ trans('projectsquare::install.agency_name') }}</label>
                        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::install.agency_name') }}" name="agency_name" />
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn valid pull-right">
                            {{ trans('projectsquare::generic.next') }} <i class="glyphicon glyphicon glyphicon-arrow-right"></i>
                        </button>
                    </div>

                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>
@endsection