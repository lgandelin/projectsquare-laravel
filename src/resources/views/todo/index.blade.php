@extends('projectsquare::default')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">{{ trans('projectsquare::dashboard.panel_title') }}</a></li>
        <li class="active">{{ trans('projectsquare::tasks.tasks') }}</li>
    </ol>

    <div class="page-header">
        <h1>{{ trans('projectsquare::tasks.todo-list') }}</h1>
    </div>

    <div class="task-template">

        <div class="col-md-12">
            <div class="block">
                <div class="block-content">
                    <form method="post" action="{{ route('to_do_store') }}">
                        <label for="name">{{ trans('projectsquare::tasks.new-task') }}</label> : <input type="text" class="form-control new-task"  name="name" id="name" required />
                        <div class="sent">
                            <input type="submit" class="btn btn-success" value="{{ trans('projectsquare::generic.add') }}" />
                        </div>
                        {!! csrf_field() !!}
                    </form>
                    <ul class="tasks">
                        @foreach ($tasks as $task)
                            <li class="task">
                                <span class="name @if($task->status == true)task-status-completed @endif">{{ $task->name }}</span>
                                <div>
                                    <form method="post" action="{{ route('to_do_update') }}">
                                        <input class="status" type="checkbox" name="status" @if($task->status == true) checked @endif>
                                        <input type="hidden" name="id" value="{{ $task->id }}" />
                                        {!! csrf_field() !!}
                                    </form>
                                </div>

                                <a href="{{ route('to_do_delete', ['id' => $task->id]) }}">{{ trans('projectsquare::generic.delete') }}</a>
                            </li>

                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.status').click(function() {
                $(this).parent().submit();
            });
        })
    </script>
@endsection