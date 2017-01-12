@extends('projectsquare::default')

@section('content')
    <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>{{ trans('projectsquare::projects.add_project') }}</h1>
            </div>

            @include('projectsquare::agency.projects.form', [
                'form_action' => route('projects_store'),
            ])
        </div>
    </div>
@endsection