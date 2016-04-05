@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'planning'])

    <div class="settings-template">
        <h1 class="page-header">{{ trans('projectsquare::project.planning') }}</h1>

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

    </div>
@endsection