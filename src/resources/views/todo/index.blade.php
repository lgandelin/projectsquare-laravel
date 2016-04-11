@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::tasks.tasks') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::tasks.tasks') }}</h1>
    </div>

    <div class="task-template">
        <div class="col-md-12">
            <div class="block">
                <div class="block-content">
                    <ul class="tasks">
                        @foreach ($tasks as $task)
                            <li class="task" data-id="{{ $task->id }}" data-status="{{ $task->status }}">
                                <span class="name @if($task->status == true)task-status-completed @endif">{{ $task->name }}</span>
                                <input type="hidden" name="id" value="{{ $task->id }}" />
                                <span class="glyphicon glyphicon-remove btn-delete-task"></span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="form-inline">
                        <div class="form-group">
                            <label for="name">{{ trans('projectsquare::tasks.new-task') }}</label> : <input type="text" class="form-control new-task"  name="name" id="name" required />
                            <input type="submit" class="btn btn-success btn-valid-create-task" value="{{ trans('projectsquare::generic.add') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tasks.js') }}"></script>

    @include('projectsquare::templates.new-task')

@endsection