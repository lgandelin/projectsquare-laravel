@extends('gateway::default')

@section('content')
    @include('gateway::includes.project_bar', ['active' => 'cms'])

    <div class="project-template">
        <h1 class="page-header">{{ trans('gateway::project.cms') }}</h1>

        <iframe id="cms-iframe" class="cms-iframe" src="{{ $project->website_back_url }}" frameborder="0"></iframe>
    </div>
@endsection

<script type="text/javascript">
    function resize()
    {
        var iframe = document.getElementById('cms-iframe');
        iframe.height = iframe.contentWindow.document.body.scrollHeight;
    }
</script>
