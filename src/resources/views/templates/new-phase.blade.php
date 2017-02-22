<script id="phase-template" type="text/x-handlebars-template">
    <div class="phase" data-id="" data-name="@{{ name }}">
        <div class="phase-wrapper">
            <span class="name">@{{ name }}</span>
            <a href="#" class="btn cancel delete-phase"></a>
            <span class="glyphicon glyphicon-triangle-top toggle-tasks"></span>
        </div>

        <div class="tasks">
            <div class="placeholder add-task">Ajouter une tâche</div>
        </div>
    </div>
</script>