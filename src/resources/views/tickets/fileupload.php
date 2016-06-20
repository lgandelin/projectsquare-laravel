<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td width="250">
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td width="150">
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td width="275">
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Démarrer</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Annuler</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td width="250">
            {% if (file.thumbnailUrl) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" target="_blank"><img class="thumbnail" src="{%=file.thumbnailUrl%}" alt="{%=file.name%}" width="135" height="80"></a>
            {% } %}
        </td>
        <td>
            {% if (file.url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" target="_blank" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
            {% } else { %}
                <span>{%=file.name%}</span>
            {% } %}
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td width="150">{%=o.formatFileSize(file.size)%}</td>
        <td width="275">
            <a href="{%=file.url%}" class="btn btn-success" download="{%=file.name%}"><i class="glyphicon glyphicon-download"></i> Télécharger</a>
            {% if (file.deleteUrl) { %}
                <a href="{%=file.deleteUrl%}" class="btn btn-danger btn-delete"><i class="glyphicon glyphicon-remove"></i> Supprimer</a>
                <!--<input type="checkbox" name="delete" value="1" class="toggle">-->
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Annuler</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>