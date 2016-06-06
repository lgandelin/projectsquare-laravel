@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">{{ trans('projectsquare::my.panel_title') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::my.panel_title') }}</h1>
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

    <div>
        <form action="{{ route('my_profile_update') }}" method="post">
            <div class="form-group">
                <label for="first_name">{{ trans('projectsquare::users.first_name') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.first_name') }}" name="first_name" @if (isset($user->first_name))value="{{ $user->first_name }}"@endif />
            </div>

            <div class="form-group">
                <label for="last_name">{{ trans('projectsquare::users.last_name') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.last_name') }}" name="last_name" @if (isset($user->last_name))value="{{ $user->last_name }}"@endif />
            </div>

            <div class="form-group">
                <label for="email">{{ trans('projectsquare::users.email') }}</label>
                <input class="form-control" type="text" placeholder="{{ trans('projectsquare::users.email') }}" name="email" @if (isset($user->email))value="{{ $user->email }}"@endif autocomplete="off" />
            </div>

            <div class="form-group">
                <label for="password">{{ trans('projectsquare::users.password') }}</label><br/>
                <input class="form-control" type="password" placeholder="@if (isset($user->id)){{ trans('projectsquare::users.password_leave_empty') }}@else{{ trans('projectsquare::users.password') }}@endif" name="password" autocomplete="off" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-ok"></i> {{ trans('projectsquare::generic.valid') }}
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('projectsquare::generic.back') }}</a>
            </div>

            {!! csrf_field() !!}
        </form>
    </div>
@endsection