<div class="page-header">
    <h1>{{ trans('projectsquare::projects.tasks') }}</h1>
</div>

<div class="phases-list">
    <div class="phases">
        @foreach ($phases as $phase)
            <div class="phase" id="{{ $phase->id }}">
                {{ $phase->name }}
                <a href="#" class="btn cancel btn-delete"></a>
            </div>
        @endforeach
    </div>
    <div class="placeholder add-phase">Ajouter une phase</div>
</div>

@include ('projectsquare::templates.new-phase')

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/tasks.js') }}"></script>
@endsection