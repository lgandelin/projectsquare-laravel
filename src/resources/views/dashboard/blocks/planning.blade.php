<div class="block planning">
    <h3>{{ trans('projectsquare::dashboard.planning') }}
        @include('projectsquare::includes.tooltip', [
            'text' => trans('projectsquare::tooltips.planning')
        ])
        <a href="{{ route('planning') }}" class="all pull-right"></a>
        <a href="#" class="glyphicon glyphicon-move move-widget pull-right"></a>
    </h3>
    <div class="block-content loading">
        <div id="planning"></div>
    </div>
</div>