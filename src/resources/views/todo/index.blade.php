@extends('gateway::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="active">Tickets</li>
    </ol>

    <div class="page-header">
        <h1>Todo</h1>
    </div>

    <div class="col-md-12">
        <div class="block">
            <div class="block-content"></div>
            <form method="post" action="{{ route('to_do_store') }}">
                <label for="name">Nouvelle tâche</label> : <input type="text" name="name" id="name"/>
                <div class="sent">
                    <input type="submit" value="Ajouter" />
                </div>
                {!! csrf_field() !!}
            </form>
            <ul>
                @foreach ($tasks as $task)
                    <li>
                        @if ($task->status == true)<del>@endif
                        {{ $task->name }}
                        @if ($task->status == true)</del>@endif
                    </li>

                @endforeach
            </ul>


        </div>
    </div>
@endsection