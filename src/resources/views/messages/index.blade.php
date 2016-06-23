@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Messages</li>
    </ol>-->
     <div class="content-page">
        <div class="templates">
            <div class="page-header">
                <h1>Messages</h1>

                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Projet</th>
                            <th>Auteur</th>
                            <th>Titre</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @foreach ($conversations as $conversation)
                        <tr>
                            <td >{{ $conversation->id }}</td>
                            <td >{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</td>
                            <td><span class="text">{{ $conversation->project->name }}</span></td>
                            <td><span class="text">@if (isset($conversation->messages[0])){{ $conversation->messages[0]->user->complete_name }}@endif</td>
                            <td><span class="text">{{ $conversation->title }}</span></td>
                            <td align="right">
                                <a href="{{ route('conversation', ['id' => $conversation->id]) }}" class="btn btn-primary see-more"></a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="text-center">
                    {!! $conversations->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection