<script id="task-template" type="text/x-handlebars-template">
    <div class="task" data-id="@{{ id }}" data-name="@{{ name }}" data-phase="@{{ phase }}">
        <div class="task-wrapper">
            <span class="name">@{{ name }}</span>
            <a href="#" class="btn cancel btn-delete delete-task"></a>
        </div>
    </div>
</script>