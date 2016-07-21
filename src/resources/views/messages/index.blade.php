@extends('projectsquare::default')

@section('content')
        <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Messages</li>
    </ol>-->
<div class="content-page">
    <div class="templates messages-template">
        <div class="page-header">
            <h1>Messages</h1>
        </div>

        <form method="get">
            <div class="row">

                <h2>Filtres</h2>

                <div class="form-group col-md-2">
                    <select class="form-control" name="filter_project" id="filter_project">
                        <option value="">{{ trans('projectsquare::tickets.filters.by_project') }}</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @if ($filters['project'] == $project->id)selected="selected" @endif>{{ $project->client->name }} - {{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input class="btn button" type="submit" value="{{ trans('projectsquare::generic.valid') }}" />
                </div>
            </div>
        </form>
        <hr/>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Auteur</th>
                    <th>Titre</th>
                    <th>Action</th>
                </tr>
                </thead>
                @foreach ($conversations as $conversation)
                    <tr>
                        <td class="priorities" style="border-left: 5px solid {{ $conversation->project->color }}"></td>
                        <td >{{ $conversation->id }}</td>
                        <td >{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</td>
                        <td><span class="text">{{ $conversation->project->client->name }}</span></td>
                        <td>
                            @include('projectsquare::includes.avatar', [
                                'id' => $conversation->messages[sizeof($conversation->messages) - 1]->user->id,
                                'name' => $conversation->messages[sizeof($conversation->messages) - 1]->user->complete_name
                            ])
                        </td>
                        <td><span class="text">{{ $conversation->title }}</span></td>
                        <td align="right">
                            <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-primary see-more"></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="text-center">
            {!! $conversations->render() !!}
        </div>
    </div>
</div>
<script src="{{ asset('js/messages.js') }}"></script>
@endsection