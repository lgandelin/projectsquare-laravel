<script id="task-template" type="text/x-handlebars-template">
    <div class="task" data-id="@{{ id }}" data-name="@{{ name }}" data-phase="@{{ phase }}">
        <div class="task-wrapper">
            <span class="name">@{{ name }}</span>
            <a href="#" class="btn cancel delete-task"></a>
            <input class="input-task-duration" type="text" placeholder="durée en j." />
        </div>
    </div>
</script>