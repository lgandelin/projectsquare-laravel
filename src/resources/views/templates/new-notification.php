<script id="notification-template" type="text/x-handlebars-template">
    <div class="notification" data-id="{{id}}">
        <span class="date">{{time}}</span>
        <span class="badge badge-primary type">
            {{#ifCond type 'MESSAGE_CREATED'}}
                {{ trans('projectsquare::top_bar.new_message') }}
            {{/ifCond}}

            {{#ifCond type 'EVENT_CREATED'}}
                {{ trans('projectsquare::top_bar.new_event') }}
            {{/ifCond}}

            {{#ifCond type 'TICKET_CREATED'}}
                {{ trans('projectsquare::top_bar.new_ticket') }}
            {{/ifCond}}

            {{#ifCond type 'FILE_UPLOADED'}}
                {{ trans('projectsquare::top_bar.new_file') }}
            {{/ifCond}}
        </span>
        <span class="description">
            {{#ifCond type 'MESSAGE_CREATED'}}
                {{ trans('projectsquare::top_bar.new_message_created') }} <strong>{{ author_name }}</strong>
            {{/ifCond}}

            {{#ifCond type 'EVENT_CREATED'}}
                 {{ trans('projectsquare::top_bar.new_event_created') }} <strong>{{ event_name }}</strong>
            {{/ifCond}}

            {{#ifCond type 'TICKET_CREATED'}}
                 {{ trans('projectsquare::top_bar.new_ticket_created') }} <strong>{{ ticket_title }}</strong>
            {{/ifCond}}

            {{#ifCond type 'FILE_UPLOADED'}}
                 {{ trans('projectsquare::top_bar.new_file_created') }} <strong>{{ file_name }}</strong>
            {{/ifCond}}
            <br/>
            <a class="btn btn-sm button" href="{{ link }}"><span class="glyphicon glyphicon-eye-open"></span>voir</a>
            <span class="glyphicon glyphicon-remove pull-right status not-read"></span>
        </span>
    </div>
</script>