@extends('projectsquare::default')

@section('content')
    <!--<ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::todos.todos') }}</li>
    </ol>-->
    <div class="templates">
        <div class="page-header">
            <h1>{{ trans('projectsquare::todos.todos') }}</h1>
        </div>

        <div class="todo-template">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        <ul class="todos">
                            @foreach ($todos as $todo)
                                <li class="todo" data-id="{{ $todo->id }}" data-status="{{ $todo->status }}">
                                    <span class="name @if($todo->status == true)todo-status-completed @endif">{{ $todo->name }}</span>
                                    <input type="hidden" name="id" value="{{ $todo->id }}" />
                                    <span class="glyphicon glyphicon-remove btn-delete-todo"></span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="form-inline">
                            <div class="form-group">
                                <label for="name">{{ trans('projectsquare::todos.new-todo') }}</label> :
                                <input type="text" class="form-control new-todo"  name="name" id="name" required autocomplete="off" />
                                <input type="submit" class="btn btn-success btn-valid-create-todo" value="{{ trans('projectsquare::generic.add') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('projectsquare::templates.new-todo')
    </div>

@endsection