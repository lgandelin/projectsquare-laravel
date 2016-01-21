@extends('gateway::default')

@section('content')
<ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}">{{ trans('gateway::dashboard.panel_title') }}</a></li>
    <li><a href="{{ route('agency_index') }}">{{ trans('gateway::agency.panel_title') }}</a></li>
    <li><a href="{{ route('projects_index') }}">{{ trans('gateway::projects.projects_list') }}</a></li>
    <li class="active">{{ trans('gateway::projects.edit_project') }}</li>
</ol>

<div class="page-header">
    <h1>{{ trans('gateway::projects.edit_project') }}</h1>
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

@include('gateway::agency.projects.form', [
'form_action' => route('projects_update'),
'project_id' => $project->id,
'project_name' => $project->name
])

@endsection