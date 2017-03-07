<script id="task-template" type="text/x-handlebars-template">
    <div class="task" data-id="@{{ id }}" data-phase="@{{ phase }}" data-temp="1">
        <div class="task-wrapper">
            <input type="text" class="input-task-name" value="@{{ name }}" />
            <a tabindex="-1" href="#" class="btn cancel delete-task"></a>
            <input class="input-task-duration" type="text" placeholder="durée en j." />
        </div>
    </div>
</script>