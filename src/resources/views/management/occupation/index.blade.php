@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates occupation-template">

            <div class="page-header col-lg-12 col-md-12">
                <div class="row">
                    <h1>{{ trans('projectsquare::occupation.occupation') }}</h1>
                </div>
            </div>

            <form method="get">
                <div class="row">
                    <h2>{{ trans('projectsquare::tasks.filters.filters') }}</h2>

                    <div class="form-group col-md-2">
                        <select class="form-control" name="filter_role" id="filter_role">
                            <option value="">{{ trans('projectsquare::occupation.filters.by_role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @if ($filters['role'] == $role->id)selected="selected" @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input class="btn button" type="submit" value="{{ trans('projectsquare::generic.valid') }}" />
                    </div>
                </div>
            </form>

            <hr/>

            @include('projectsquare::management.occupation.includes.calendar')
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/occupation.js') }}"></script>
@endsection