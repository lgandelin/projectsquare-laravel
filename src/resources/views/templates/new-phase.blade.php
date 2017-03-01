<script id="phase-template" type="text/x-handlebars-template">
    <div class="phase" data-id="" data-name="@{{ name }}" data-duration="0">
        <div class="phase-wrapper">
            <span class="name">@{{ name }}</span>
            <a href="#" tabindex="-1" class="btn cancel delete-phase"></a>
            <span class="glyphicon glyphicon-triangle-top toggle-tasks"></span>
            <span class="phase-duration"><span class="value">0</span> jour(s)</span>
        </div>

        <div class="tasks">
            <div class="placeholder add-task">Ajouter une tâche</div>
        </div>
    </div>
</script>