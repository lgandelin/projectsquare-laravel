<?php

Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::group(['middleware' => 'web', 'namespace' => 'Webaccess\ProjectSquareLaravel\Http\Controllers'], function () {

    //DASHBOARD
    Route::get('/', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
    Route::get('/login', array('as' => 'login', 'uses' => 'LoginController@login'));
    Route::post('/login', array('as' => 'login_handler', 'uses' => 'LoginController@authenticate'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'LoginController@logout'));
    Route::get('/forgotten_password', array('as' => 'forgotten_password', 'uses' => 'LoginController@forgotten_password'));
    Route::post('/forgotten_password_handler', array('as' => 'forgotten_password_handler', 'uses' => 'LoginController@forgotten_password_handler'));

    //CONFIG
    Route::get('/config', array('as' => 'config', 'uses' => 'ConfigController@index', 'middleware' => 'after_config'));
    Route::post('/config', array('as' => 'config_handler', 'uses' => 'ConfigController@config_handler', 'middleware' => 'after_config'));
    Route::get('/config_confirmation', array('as' => 'config_confirmation', 'uses' => 'ConfigController@confirmation'));

    //NOTIFICATIONS
    Route::post('/read_notification', array('as' => 'read_notification', 'uses' => 'NotificationController@read'));

    //MY PROFILE
    Route::get('/my', array('as' => 'my', 'uses' => 'MyController@index'));
    Route::post('/my/udpate_profile', array('as' => 'my_profile_update', 'uses' => 'MyController@udpate_profile'));
    Route::post('/my/upload_avatar', array('as' => 'my_profile_upload_avatar', 'uses' => 'MyController@upload_avatar'));
    Route::post('/my/update_notifications', array('as' => 'my_profile_update_notifications', 'uses' => 'MyController@update_notifications'));

    //BETA FORM
    Route::post('/beta_form', array('as' => 'beta_form', 'uses' => 'BaseController@betaForm'));

    //PROJECTS
    Route::get('/projects/{uuid}/cms', array('as' => 'project_cms', 'uses' => 'ProjectController@index', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/tasks', array('as' => 'project_tasks', 'uses' => 'ProjectController@tasks', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/tasks/{task_uuid?}', array('as' => 'project_tasks_edit', 'uses' => 'ProjectController@tasks_edit', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/tickets', array('as' => 'project_tickets', 'uses' => 'ProjectController@tickets', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/tickets/{ticket_uuid?}', array('as' => 'project_tickets_edit', 'uses' => 'ProjectController@tickets_edit', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/monitoring', array('as' => 'project_monitoring', 'uses' => 'ProjectController@monitoring', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/project_seo', array('as' => 'project_seo', 'uses' => 'ProjectController@seo', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/messages', array('as' => 'project_messages', 'uses' => 'ProjectController@messages', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/calendar', array('as' => 'project_calendar', 'uses' => 'Project\CalendarController@index', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/files', array('as' => 'project_files', 'uses' => 'Project\FilesController@index', 'middleware' => 'change_current_project'));
    Route::post('/projects/files/upload', array('as' => 'projects_files_upload', 'uses' => 'Project\FilesController@upload'));
    Route::get('/projects/files/delete/{id}', array('as' => 'projects_files_delete', 'uses' => 'Project\FilesController@delete'));
    Route::get('/projects/calendar/get_step', array('as' => 'steps_get_infos', 'uses' => 'Project\CalendarController@get_step'));
    Route::post('/projects/calendar/create', array('as' => 'steps_create', 'uses' => 'Project\CalendarController@create'));
    Route::post('/projects/calendar/update', array('as' => 'steps_update', 'uses' => 'Project\CalendarController@update'));
    Route::post('/projects/calendar/delete', array('as' => 'steps_delete', 'uses' => 'Project\CalendarController@delete'));
    Route::get('/project/users', array('as' => 'project_users', 'uses' => 'ProjectController@get_users'));
    Route::get('/projects/{uuid}/progress', array('as' => 'project_progress', 'uses' => 'ProjectController@progress', 'middleware' => 'change_current_project'));
    Route::get('/projects/{uuid}/spent-time', array('as' => 'project_spent_time', 'uses' => 'ProjectController@spent_time', 'middleware' => 'change_current_project'));

    Route::post('/projects/switch', array('as' => 'projects_client_switch', 'uses' => 'ProjectController@client_switch'));

    //TOOLS
    Route::get('/tools/tickets', array('as' => 'tickets_index', 'uses' => 'Tools\TicketController@index'));
    Route::get('/tools/tickets/add', array('as' => 'tickets_add', 'uses' => 'Tools\TicketController@add'));
    Route::post('/tools/tickets/add', array('as' => 'tickets_store', 'uses' => 'Tools\TicketController@store'));
    Route::get('/tools/tickets/{uuid}', array('as' => 'tickets_edit', 'uses' => 'Tools\TicketController@edit'));
    Route::post('/tools/tickets/upload_file', array('as' => 'tickets_edit_upload_file', 'uses' => 'Tools\TicketController@upload_file'));
    Route::get('/tools/tickets/delete_file/{id}', array('as' => 'tickets_edit_delete_file', 'uses' => 'Tools\TicketController@delete_file'));
    Route::post('/tools/tickets', array('as' => 'tickets_update', 'uses' => 'Tools\TicketController@update'));
    Route::post('/tools/tickets_infos', array('as' => 'tickets_update_infos', 'uses' => 'Tools\TicketController@updateInfos'));
    Route::post('/tools/tickets_unallocate', array('as' => 'tickets_unallocate', 'uses' => 'Tools\TicketController@unallocate'));
    Route::get('/tools/delete_ticket/{uuid}', array('as' => 'tickets_delete', 'uses' => 'Tools\TicketController@delete'));

    Route::group(['middleware' => 'user'], function () {
        Route::get('/tools/tasks', array('as' => 'tasks_index', 'uses' => 'Tools\TaskController@index'));
        Route::get('/tools/tasks/add', array('as' => 'tasks_add', 'uses' => 'Tools\TaskController@add'));
        Route::post('/tools/tasks/add', array('as' => 'tasks_store', 'uses' => 'Tools\TaskController@store'));
        Route::get('/tools/tasks/{uuid}', array('as' => 'tasks_edit', 'uses' => 'Tools\TaskController@edit'));
        Route::post('/tools/tasks/update', array('as' => 'tasks_update', 'uses' => 'Tools\TaskController@update'));
        Route::post('/tools/tasks_unallocate', array('as' => 'tasks_unallocate', 'uses' => 'Tools\TaskController@unallocate'));
        Route::get('/tools/delete_task/{uuid}', array('as' => 'tasks_delete', 'uses' => 'Tools\TaskController@delete'));

        Route::get('/tools/monitoring', array('as' => 'monitoring_index', 'uses' => 'Tools\MonitoringController@index'));

        Route::get('/tools/planning', array('as' => 'planning', 'uses' => 'Tools\PlanningController@index'));
        Route::get('/tools/planning/get_event', array('as' => 'events_get_infos', 'uses' => 'Tools\PlanningController@get_event'));
        Route::post('/tools/planning/create', array('as' => 'events_create', 'uses' => 'Tools\PlanningController@create'));
        Route::post('/tools/planning/update', array('as' => 'events_update', 'uses' => 'Tools\PlanningController@update'));
        Route::post('/tools/planning/delete', array('as' => 'events_delete', 'uses' => 'Tools\PlanningController@delete'));

        Route::get('/tools/conversations', array('as' => 'conversations_index', 'uses' => 'Tools\MessageController@index'));
        Route::post('/tools/conversations/reply', array('as' => 'conversations_reply', 'uses' => 'Tools\MessageController@reply'));
        Route::get('/tools/conversations/{id}', array('as' => 'conversations_view', 'uses' => 'Tools\MessageController@view'));
        Route::post('/toolsconversations', array('as' => 'add_conversation', 'uses' => 'Tools\MessageController@addConversation'));

        Route::post('/tools/todos/create', array('as' => 'todos_create', 'uses' => 'Tools\TodoController@create'));
        Route::post('/tools/todos/update', array('as' => 'todos_update', 'uses' => 'Tools\TodoController@update'));
        Route::post('/tools/todos/delete', array('as' => 'todos_delete', 'uses' => 'Tools\TodoController@delete'));
    });

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/management/progress', array('as' => 'progress', 'uses' => 'Management\ProgressController@index'));
        Route::get('/management/spent-time', array('as' => 'spent_time', 'uses' => 'Management\SpentTimeController@index'));
        Route::get('/management/occupation', array('as' => 'occupation', 'uses' => 'Management\OccupationController@index'));

        Route::get('/administration/users', array('as' => 'users_index', 'uses' => 'Administration\UserController@index'));
        Route::get('/administration/roles', array('as' => 'roles_index', 'uses' => 'Administration\RoleController@index'));
        Route::get('/administration/roles/add', array('as' => 'roles_add', 'uses' => 'Administration\RoleController@add'));
        Route::post('/administration/roles/add', array('as' => 'roles_store', 'uses' => 'Administration\RoleController@store'));
        Route::get('/administration/roles/{id}', array('as' => 'roles_edit', 'uses' => 'Administration\RoleController@edit'));
        Route::post('/administration/roles', array('as' => 'roles_update', 'uses' => 'Administration\RoleController@update'));
        Route::get('/administration/roles/delete/{id}', array('as' => 'roles_delete', 'uses' => 'Administration\RoleController@delete'));

        Route::get('/administration/projects', array('as' => 'projects_index', 'uses' => 'Administration\ProjectController@index'));
        Route::get('/administration/projects/add', array('as' => 'projects_add', 'uses' => 'Administration\ProjectController@add'));
        Route::post('/administration/projects/add', array('as' => 'projects_store', 'uses' => 'Administration\ProjectController@store'));
        Route::get('/administration/projects/{uuid}', array('as' => 'projects_edit', 'uses' => 'Administration\ProjectController@edit'));
        Route::get('/administration/projects/{uuid}/team', array('as' => 'projects_edit_team', 'uses' => 'Administration\ProjectController@edit_team'));
        Route::get('/administration/projects/{uuid}/tasks', array('as' => 'projects_edit_tasks', 'uses' => 'Administration\ProjectController@edit_tasks'));
        Route::get('/administration/projects/{uuid}/attribution', array('as' => 'projects_edit_attribution', 'uses' => 'Administration\ProjectController@edit_attribution'));
        Route::get('/administration/projects/{uuid}/config', array('as' => 'projects_edit_config', 'uses' => 'Administration\ProjectController@edit_config'));
        Route::post('/administration/projects', array('as' => 'projects_update', 'uses' => 'Administration\ProjectController@update'));
        Route::post('/administration/projects/config', array('as' => 'projects_update_config', 'uses' => 'Administration\ProjectController@update_config'));
        Route::get('/administration/projects/delete/{uuid}', array('as' => 'projects_delete', 'uses' => 'Administration\ProjectController@delete'));
        Route::post('/administration/projects/add_user', array('as' => 'projects_add_user', 'uses' => 'Administration\ProjectController@add_user'));
        Route::get('/administration/projects/delete_user/{uuid}/{user_id}', array('as' => 'projects_delete_user', 'uses' => 'Administration\ProjectController@delete_user'));
        Route::post('/administration/projects/tasks', array('as' => 'projects_update_tasks', 'uses' => 'Administration\ProjectController@update_tasks'));
        Route::post('/administration/projects/allocate_and_schedule_task', array('as' => 'projects_allocate_and_schedule_task', 'uses' => 'Administration\ProjectController@allocate_and_schedule_task'));
        Route::post('/administration/projects/import_phases_and_tasks_from_text', array('as' => 'projects_import_phases_and_tasks_from_text', 'uses' => 'Administration\ProjectController@import_phases_and_tasks_from_text'));

        Route::get('/administration/clients', array('as' => 'clients_index', 'uses' => 'Administration\ClientController@index'));
        Route::get('/administration/clients/add', array('as' => 'clients_add', 'uses' => 'Administration\ClientController@add'));
        Route::post('/administration/clients/add', array('as' => 'clients_store', 'uses' => 'Administration\ClientController@store'));
        Route::get('/administration/clients/{uuid}', array('as' => 'clients_edit', 'uses' => 'Administration\ClientController@edit'));
        Route::post('/administration/clients', array('as' => 'clients_update', 'uses' => 'Administration\ClientController@update'));
        Route::get('/administration/clients/delete/{uuid}', array('as' => 'clients_delete', 'uses' => 'Administration\ClientController@delete'));

        Route::get('/administration/clients/{uuid}/add_user', array('as' => 'clients_add_user', 'uses' => 'Administration\ClientController@add_user'));
        Route::post('/administration/clients/store_user', array('as' => 'clients_store_user', 'uses' => 'Administration\ClientController@store_user'));
        Route::post('/administration/clients/add_ajax', array('as' => 'clients_add_ajax', 'uses' => 'Administration\ClientController@store_ajax'));
        Route::get('/administration/clients/{uuid}/edit_user/{user_id}', array('as' => 'clients_edit_user', 'uses' => 'Administration\ClientController@edit_user'));
        Route::post('/administration/clients/update_user', array('as' => 'clients_update_user', 'uses' => 'Administration\ClientController@update_user'));
        Route::get('/administration/clients/{uuid}/delete_user/{user_id}', array('as' => 'clients_delete_user', 'uses' => 'Administration\ClientController@delete_user'));

        Route::get('/administration/users', array('as' => 'users_index', 'uses' => 'Administration\UserController@index'));
        Route::get('/administration/users/add', array('as' => 'users_add', 'uses' => 'Administration\UserController@add'));
        Route::post('/administration/users/add', array('as' => 'users_store', 'uses' => 'Administration\UserController@store'));
        Route::get('/administration/users/{uuid}', array('as' => 'users_edit', 'uses' => 'Administration\UserController@edit'));
        Route::post('/administration/users', array('as' => 'users_update', 'uses' => 'Administration\UserController@update'));
        Route::get('/administration/users/delete/{uuid}', array('as' => 'users_delete', 'uses' => 'Administration\UserController@delete'));
        Route::get('/administration/user_generate_new_password/{uuid}', array('as' => 'users_generate_password', 'uses' => 'Administration\UserController@generate_password'));

        Route::get('/administration/ticket_types', array('as' => 'ticket_types_index', 'uses' => 'Administration\TicketTypeController@index'));
        Route::get('/administration/ticket_types/add', array('as' => 'ticket_types_add', 'uses' => 'Administration\TicketTypeController@add'));
        Route::post('/administration/ticket_types/add', array('as' => 'ticket_types_store', 'uses' => 'Administration\TicketTypeController@store'));
        Route::get('/administration/ticket_types/{id}', array('as' => 'ticket_types_edit', 'uses' => 'Administration\TicketTypeController@edit'));
        Route::post('/administration/ticket_types', array('as' => 'ticket_types_update', 'uses' => 'Administration\TicketTypeController@update'));
        Route::get('/administration/ticket_types/delete/{id}', array('as' => 'ticket_types_delete', 'uses' => 'Administration\TicketTypeController@delete'));

        Route::get('/administration/ticket_statuses', array('as' => 'ticket_statuses_index', 'uses' => 'Administration\TicketStatusController@index'));
        Route::get('/administration/ticket_statuses/add', array('as' => 'ticket_statuses_add', 'uses' => 'Administration\TicketStatusController@add'));
        Route::post('/administration/ticket_statuses/add', array('as' => 'ticket_statuses_store', 'uses' => 'Administration\TicketStatusController@store'));
        Route::get('/administration/ticket_statuses/{id}', array('as' => 'ticket_statuses_edit', 'uses' => 'Administration\TicketStatusController@edit'));
        Route::post('/administration/ticket_statuses', array('as' => 'ticket_statuses_update', 'uses' => 'Administration\TicketStatusController@update'));
        Route::get('/administration/ticket_statuses/delete/{id}', array('as' => 'ticket_statuses_delete', 'uses' => 'Administration\TicketStatusController@delete'));

        Route::get('/administration/settings', array('as' => 'settings_index', 'uses' => 'Administration\SettingsController@index'));
        Route::post('/administration/settings', array('as' => 'settings_update', 'uses' => 'Administration\SettingsController@update'));
    });
});