<div class="block planning">
    <h3>{{ __('projectsquare::dashboard.planning') }}
        @include('projectsquare::includes.tooltip', [
            'text' => __('projectsquare::tooltips.planning')
        ])
        <a href="{{ route('planning') }}" class="all pull-right" title="{{ __('projectsquare::dashboard.see_planning') }}"></a>
        <a href="#" class="glyphicon glyphicon-move move-widget pull-right" title="{{ __('projectsquare::dashboard.move_widget') }}"></a>
    </h3>
    <div class="block-content loading">
        <div id="planning"></div>
    </div>
</div>