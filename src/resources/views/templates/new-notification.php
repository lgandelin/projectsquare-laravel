<script id="notification-template" type="text/x-handlebars-template">
    <div class="notification" data-id="{{id}}">
        <span class="date">{{time}}</span>
        <span class="badge badge-primary type">{{type}}</span>
        <span class="description">
            Nouvelle notification sur l'entité : <a href="#">{{entityID}}</a>
            <span class="glyphicon glyphicon-eye-open pull-right status not-read"></span>
        </span>
    </div>
</script>