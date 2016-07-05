<script id="notification-template" type="text/x-handlebars-template">
    <div class="notification" data-id="{{id}}">
        <span class="date">{{time}}</span>
        <span class="badge badge-primary type">
            {{#ifCond type 'MESSAGE_CREATED'}}
                Nouveau message
            {{/ifCond}}

            {{#ifCond type 'EVENT_CREATED'}}
                Nouvel évenement
            {{/ifCond}}
        </span>
        <span class="description">
            {{#ifCond type 'MESSAGE_CREATED'}}
                Nouveau message créé par : <strong>{{ author_name }}</strong>
            {{/ifCond}}

            {{#ifCond type 'EVENT_CREATED'}}
                Nouvel évènement créé : <strong>{{ event_name }}</strong>
            {{/ifCond}}
            <br/>
            <a class="btn btn-sm button" href="{{ link }}"><span class="glyphicon glyphicon-eye-open"></span>voir</a>
            <span class="glyphicon glyphicon-remove pull-right status not-read"></span>
        </span>
    </div>
</script>