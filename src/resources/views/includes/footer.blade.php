    @include('projectsquare::includes.beta_form')

    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/vendor/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.handlebars.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.colorpicker.js') }}"></script>
    <script src="{{ asset('js/vendor/tooltipster.bundle.min.js') }}"></script>
    <script src="{{ asset('js/vendor/powertour.3.2.0.min.js') }}"></script>
    <script src="{{ asset('js/todos.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}?v={{ env('ASSETS_VERSION', '20170901') }}"></script>
    <script src="{{ asset('js/global.js') }}?v={{ env('ASSETS_VERSION', '20170901') }}"></script>

    @include('projectsquare::templates.new-todo')
    @include('projectsquare::templates.new-notification')
    @include('projectsquare::includes.js_routes')

    @yield('scripts')

    <input type="hidden" id="csrf_token" value="{!! csrf_token() !!}" />

    </body>
</html>
