<script id="notification-template" type="text/x-handlebars-template">
    <div class="notification" data-id="@{{ id }}">
        <span class="project" style="background: @{{ projectColor }}">
            @{{ projectName }}
            <span class="glyphicon glyphicon-remove pull-right notification-status not-read"></span>
        </span>

        <a class="link" href="@{{ link }}">
            @{{#ifCond hasAvatar true}}
                <img class="avatar" src="@{{ authorAvatar }}" title="@{{ authorCompleteName }}" alt="@{{ authorCompleteName }}" />
            @{{/ifCond}}

            <span class="title">@{{ title }}</span>
            <span class="description">@{{{ description }}}</span>
            <span class="relative-date">@{{ relative_date }}</span>
        </a>
    </div>
</script>