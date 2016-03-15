    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/vendor/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.handlebars.min.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/messages.js') }}"></script>
    <script>
        var route_message_reply = "{{ route('messages_reply') }}";
        var route_add_conversation = "{{ route('add_conversation') }}";
    </script>
    <input type="hidden" id="csrf_token" value="{!! csrf_token() !!}" />
    @yield('scripts')
</body>
</html>
