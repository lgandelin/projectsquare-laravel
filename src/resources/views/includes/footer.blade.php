    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/vendor/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.handlebars.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.colorpicker.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/messages.js') }}"></script>
    <script>
        var route_message_reply = "{{ route('messages_reply') }}";
        var route_add_conversation = "{{ route('add_conversation') }}";
        var route_event_create = "{{ route('events_create') }}";
        var route_event_update = "{{ route('events_update') }}";
        var route_event_delete = "{{ route('events_delete') }}";
        var route_event_get_infos = "{{ route('events_get_infos') }}";
        var route_refresh_notifications = "{{ route('refresh_notifications') }}";
        var route_read_notification = "{{ route('read_notification') }}";
        var route_step_create = "{{ route('steps_create') }}";
        var route_step_update = "{{ route('steps_update') }}";
        var route_step_delete = "{{ route('steps_delete') }}";
        var route_step_get_infos = "{{ route('steps_get_infos') }}";
    </script>
    <input type="hidden" id="csrf_token" value="{!! csrf_token() !!}" />
    @yield('scripts')
</body>
</html>
