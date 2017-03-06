<script id="phase-template" type="text/x-handlebars-template">
    <div class="phase" data-id="@{{ id }}" data-duration="0" data-temp="1">
        <div class="phase-wrapper">
            <input type="text" class="input-phase-name" value="@{{ name }}" />
            <a href="#" tabindex="-1" class="btn cancel delete-phase"></a>
            <span class="glyphicon glyphicon-triangle-top toggle-tasks"></span>
            <span class="phase-duration"><span class="value">0</span> jour(s)</span>
        </div>

        <div class="tasks">
            <div class="placeholder add-task">Ajouter une tâche</div>
        </div>
    </div>
</script>