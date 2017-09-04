    @include('projectsquare::includes.beta_form')

    <div class="single-step" id="step-intro">
        <h3>Bienvenue sur Projectsquare !</h3>
        <p>Nous avons détecté que c'est la première fois que vous vous connectez sur votre plateforme avec cet ordinateur.<br><br/>Souhaitez vous suivre la présentation de Projectsquare ?</p>

        <div class="nav">
            <a class="btn " href="#" data-powertour-action="stop"><i class="glyphicon glyphicon-home"></i> Non merci</a>
            <a class="btn valid" href="#" data-powertour-action="next" style="float:right"><i class="glyphicon glyphicon-ok"></i> Oui, suivre la présentation</a>
        </div>
    </div>

    <div class="single-step" id="step-left-bar">
        <span class="connectorarrow-lm"></span>
        <h3>1. Barre latérale transverse</h3>
        <p>Ce menu permet un accès transverse à toutes les sections de la plateforme : projets, outils, pilotage et administration.</p>

        <div class="nav">
            <a class="btn" href="#" data-powertour-action="prev"><i class="glyphicon glyphicon-chevron-left"></i> Précédent</a>
            <a class="btn valid" href="#" data-powertour-action="next" style="float:right"><i class="glyphicon glyphicon-chevron-right"></i> Suivant</a>
        </div>
    </div>

    <div class="single-step" id="step-top-bar">
        <span class="connectorarrow-tm"></span>
        <h3>2. Barre supérieure</h3>
        <p>Ce menu permet d'accéder à votre todo-list, aux notifications reçues, ainsi qu'un accès à la page <strong>Mon compte</strong>, qui vous permettra de modifier vos informations personnelles comme votre mot de passe.</p>

        <div class="nav">
            <a class="btn" href="#" data-powertour-action="prev"><i class="glyphicon glyphicon-chevron-left"></i> Précédent</a>
            <a class="btn valid" href="#" data-powertour-action="next" style="float:right"><i class="glyphicon glyphicon-chevron-right"></i> Suivant</a>
        </div>
    </div>

    <div class="single-step" id="step-tickets-widget">
        <span class="connectorarrow-lm"></span>
        <h3>3. Mes tickets</h3>
        <p>Ce tableau liste tous les tickets de la plateforme qui vous sont assignés, tous projets confondus. Un ticket peut être créé par vos clients ou un membre de votre équipe, et représente généralement une action de votre part à réaliser sur un projet terminé (évolution, correction de bug, demande...).</p>

        <div class="nav">
            <a class="btn" href="#" data-powertour-action="prev"><i class="glyphicon glyphicon-chevron-left"></i> Précédent</a>
            <a class="btn valid" href="#" data-powertour-action="next" style="float:right"><i class="glyphicon glyphicon-chevron-right"></i> Suivant</a>
        </div>
    </div>

    <div class="single-step" id="step-tasks-widget">
        <span class="connectorarrow-rm"></span>
        <h3>4. Mes tâches</h3>
        <p>Ce tableau liste toutes les tâches de la plateforme qui vous sont assignées, tous projets confondus. Une tâche représente une action à effectuer lors de la réalisation d'un projet, par vous ou un membre de votre équipe.</p>

        <div class="nav">
            <a class="btn" href="#" data-powertour-action="prev"><i class="glyphicon glyphicon-chevron-left"></i> Précédent</a>
            <a class="btn valid" href="#" data-powertour-action="next" style="float:right"><i class="glyphicon glyphicon-chevron-right"></i> Suivant</a>
        </div>
    </div>

    <div class="single-step" id="step-planning-widget">
        <span class="connectorarrow-bm"></span>
        <h3>5. Mon planning</h3>
        <p>Ce planning liste l'ensemble des choses que vous avez à faire pour les jours à venir. Il peut lister des tâches, des tickets, ou encore des évenements personnalisés comme une réunion ou un rendez-vous.</p>

        <div class="nav">
            <a class="btn" href="#" data-powertour-action="prev"><i class="glyphicon glyphicon-chevron-left"></i> Précédent</a>
            <a class="btn valid" href="#" data-powertour-action="stop" style="float:right"><i class="glyphicon glyphicon-ok"></i> Finir la présentation</a>
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/vendor/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.handlebars.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.colorpicker.js') }}"></script>
    <script src="{{ asset('js/vendor/tooltipster.bundle.min.js') }}"></script>
    <script src="{{ asset('js/todos.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
   
    <script>
        var route_message_reply = "{{ route('conversations_reply') }}";
        var route_add_conversation = "{{ route('add_conversation') }}";
        var route_event_create = "{{ route('events_create') }}";
        var route_event_update = "{{ route('events_update') }}";
        var route_event_delete = "{{ route('events_delete') }}";
        var route_event_get_infos = "{{ route('events_get_infos') }}";
        var route_read_notification = "{{ route('read_notification') }}";
        var route_step_create = "{{ route('steps_create') }}";
        var route_step_update = "{{ route('steps_update') }}";
        var route_step_delete = "{{ route('steps_delete') }}";
        var route_step_get_infos = "{{ route('steps_get_infos') }}";
        var route_todo_create = "{{ route('todos_create') }}";
        var route_todo_update = "{{ route('todos_update') }}";
        var route_todo_delete = "{{ route('todos_delete') }}";
        var route_ticket_unallocate = "{{ route('tickets_unallocate') }}";
        var route_task_unallocate = "{{ route('tasks_unallocate') }}";
        var route_beta_form = "{{ route('beta_form') }}";
        var route_project_users = "{{ route('project_users') }}";
        var route_udpate_tasks = "{{ route('projects_update_tasks') }}";
        var route_allocate_and_schedule_task = "{{ route('projects_allocate_and_schedule_task') }}";
        var route_client_create = "{{ route('clients_add_ajax') }}";
    </script>

    <input type="hidden" id="csrf_token" value="{!! csrf_token() !!}" />
    @yield('scripts')
</body>
</html>
