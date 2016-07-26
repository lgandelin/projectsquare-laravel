@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'index'])
    <div class="content-page">
        <div class="templates project-template">
            <h1 class="page-header">{{ trans('projectsquare::project.cms') }}</h1>

            @if ($project->websiteBackURL)
                <iframe id="cms-iframe" class="cms-iframe" src="{{ $project->websiteBackURL }}" frameborder="0"></iframe>
            @else
                <span>{{ trans('projectsquare::cms.url_not_configured') }}</span>
            @endif
        </div>
    </div>
@endsection