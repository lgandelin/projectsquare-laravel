@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Messages</li>
    </ol>

    <div class="page-header">
        <h1>Messages</h1>

        <table class="table table-striped table-responsive">
            @foreach ($conversations as $conversation)
                <tr>
                    <td>{{ $conversation->id }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($conversation->created_at)) }}</td>
                    <td>{{ $conversation->project->name }}</td>
                    <td>{{ $conversation->title }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection