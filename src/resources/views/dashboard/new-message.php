<script id="message-template" type="text/x-handlebars-template">
    <div class="message">
        <span class="badge">{{datetime}}</span> <span class="glyphicon glyphicon-user"></span> <span class="user_name">{{username}}</span><br>
        <p class="content">{{message}}</p>
        <a href="http://gateway.dev/project/{{id}}/messages" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-share-alt"></span> voir</a>
        <button class="btn btn-success pull-right reply-message" data-id="{{id}}" style="margin-right: 1rem;"><span class="glyphicon glyphicon-comment"></span> répondre</button>
    </div>
</script>