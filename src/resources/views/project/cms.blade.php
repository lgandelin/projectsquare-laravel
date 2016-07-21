@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'index'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.cms') }}</h1>

            @if ($project->websiteBackURL)
                <iframe id="cms-iframe" class="cms-iframe" src="{{ $project->websiteBackURL }}" frameborder="0"></iframe>
            @else
                <span>URL d'administration non configurée pour ce projet.</span>
            @endif
        </div>
    </div>
@endsection