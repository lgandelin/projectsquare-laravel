<script id="todo-template" type="text/x-handlebars-template">
    <li class="todo" data-id="@{{ id }}"><span class="name @{{#ifCond status true}}task-status-completed@{{/ifCond}}">@{{ name }}</span><input type="hidden" name="id" value="@{{ id }}" /><span class="glyphicon glyphicon-remove btn-delete-todo"></span></li>
</script>