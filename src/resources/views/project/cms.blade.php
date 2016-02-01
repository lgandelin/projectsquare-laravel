@extends('gateway::default')

@section('content')
@include('gateway::includes.project_bar')

<h1 class="page-header">{{ $project->client->name }} - {{ $project->name }}</h1>

<div class="col-md-12">
    <h3>{{ trans('gateway::project.cms') }}</h3>

    {{ $content }}
</div>

@endsection