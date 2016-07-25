<div class="block planning">
    <h3>{{ trans('projectsquare::planning.planning') }}</h3>
    @include('projectsquare::includes.tooltip', [
        'text' => trans('projectsquare::tooltips.planning')
    ])

    <a href="#" class="glyphicon glyphicon-move move-widget pull-right"></a>
    <a href="{{ route('planning') }}" class="all pull-right"></a>
    <div class="block-content loading">
        <div id="planning"></div>
    </div>
</div>