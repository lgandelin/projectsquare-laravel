@extends('projectsquare::default')

@section('content')
    @include('projectsquare::includes.project_bar', ['active' => 'index'])

    <div class="templates project-template">
        <h1 class="page-header">{{ trans('projectsquare::project.cms') }}</h1>

        @if ($project->website_back_url)
            <iframe id="cms-iframe" class="cms-iframe" src="{{ $project->website_back_url }}" frameborder="0"></iframe>
        @else
            <span>URL d'administration non configurée pour ce projet.</span>
        @endif
    </div>
@endsection

<script type="text/javascript">
    function resize()
    {
        var iframe = document.getElementById('cms-iframe');
        iframe.height = iframe.contentWindow.document.body.scrollHeight;
    }
</script>
