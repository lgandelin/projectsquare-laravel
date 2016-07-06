<script id="task-template" type="text/x-handlebars-template">
    <li class="task" data-id="{{ id }}"><span class="name {{#ifCond status true}}task-status-completed{{/ifCond}}">{{ name }}</span><input type="hidden" name="id" value="{{ id }}" /><span class="glyphicon glyphicon-remove btn-delete-task"></span></li>
</script>