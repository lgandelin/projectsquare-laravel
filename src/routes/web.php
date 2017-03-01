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

    //NOTIFICATIONS
    Route::get('/get_notifications', array('as' => 'get_notifications', 'uses' => 'NotificationController@get_notifications'));
    Route::post('/read_notification', array('as' => 'read_notification', 'uses' => 'NotificationController@read'));

    //TODOS
    Route::get('/todos', array('as' => 'todos_index', 'uses' => 'Utility\TodoController@index'));
    Route::post('/todos/create', array('as' => 'todos_create', 'uses' => 'Utility\TodoController@create'));
    Route::post('/todos/update', array('as' => 'todos_update', 'uses' => 'Utility\TodoController@update'));
    Route::post('/todos/delete', array('as' => 'todos_delete', 'uses' => 'Utility\TodoController@delete'));

    //TICKETS
    Route::get('/tickets', array('as' => 'tickets_index', 'uses' => 'Utility\TicketController@index'));
    Route::get('/tickets/add', array('as' => 'tickets_add', 'uses' => 'Utility\TicketController@add'));
    Route::post('/tickets/add', array('as' => 'tickets_store', 'uses' => 'Utility\TicketController@store'));
    Route::get('/tickets/{uuid}', array('as' => 'tickets_edit', 'uses' => 'Utility\TicketController@edit'));
    Route::post('/tickets/upload_file', array('as' => 'tickets_edit_upload_file', 'uses' => 'Utility\TicketController@upload_file'));
    Route::get('/tickets/delete_file/{id}', array('as' => 'tickets_edit_delete_file', 'uses' => 'Utility\TicketController@delete_file'));
    Route::post('/tickets', array('as' => 'tickets_update', 'uses' => 'Utility\TicketController@update'));
    Route::post('/tickets_infos', array('as' => 'tickets_update_infos', 'uses' => 'Utility\TicketController@updateInfos'));
    Route::post('/tickets_unallocate', array('as' => 'tickets_unallocate', 'uses' => 'Utility\TicketController@unallocate'));
    Route::get('/delete_ticket/{uuid}', array('as' => 'tickets_delete', 'uses' => 'Utility\TicketController@delete'));

    //TASKS
    Route::get('/tasks', array('as' => 'tasks_index', 'uses' => 'Utility\TaskController@index'));
    Route::get('/tasks/add', array('as' => 'tasks_add', 'uses' => 'Utility\TaskController@add'));
    Route::post('/tasks/add', array('as' => 'tasks_store', 'uses' => 'Utility\TaskController@store'));
    Route::get('/tasks/{uuid}', array('as' => 'tasks_edit', 'uses' => 'Utility\TaskController@edit'));
    Route::post('/tasks/update', array('as' => 'tasks_update', 'uses' => 'Utility\TaskController@update'));
    Route::post('/tasks_unallocate', array('as' => 'tasks_unallocate', 'uses' => 'Utility\TaskController@unallocate'));
    Route::get('/delete_task/{uuid}', array('as' => 'tasks_delete', 'uses' => 'Utility\TaskController@delete'));

    //PROJECTS
    Route::get('/project/{uuid}', array('as' => 'project_index', 'uses' => 'ProjectController@index', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/tasks', array('as' => 'project_tasks', 'uses' => 'ProjectController@tasks', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/tickets', array('as' => 'project_tickets', 'uses' => 'ProjectController@tickets', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/monitoring', array('as' => 'project_monitoring', 'uses' => 'ProjectController@monitoring', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/project_seo', array('as' => 'project_seo', 'uses' => 'ProjectController@seo', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/messages', array('as' => 'project_messages', 'uses' => 'ProjectController@messages', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/calendar', array('as' => 'project_calendar', 'uses' => 'Project\CalendarController@index', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/reporting', array('as' => 'project_reporting', 'uses' => 'ProjectController@reporting', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/files', array('as' => 'project_files', 'uses' => 'Project\FilesController@index', 'middleware' => 'change_current_project'));

    //PROJECT FILES
    Route::post('/project/files/upload', array('as' => 'projects_files_upload', 'uses' => 'Project\FilesController@upload'));
    Route::get('/project/files/delete/{id}', array('as' => 'projects_files_delete', 'uses' => 'Project\FilesController@delete'));

    //PROJECT CALENDAR
    Route::get('/calendar/get_step', array('as' => 'steps_get_infos', 'uses' => 'Project\CalendarController@get_step'));
    Route::post('/calendar/create', array('as' => 'steps_create', 'uses' => 'Project\CalendarController@create'));
    Route::post('/calendar/update', array('as' => 'steps_update', 'uses' => 'Project\CalendarController@update'));
    Route::post('/calendar/delete', array('as' => 'steps_delete', 'uses' => 'Project\CalendarController@delete'));

    //AGENCY
    Route::get('/agency/users', array('as' => 'users_index', 'uses' => 'Agency\UserController@index'));
    Route::get('/agency/roles', array('as' => 'roles_index', 'uses' => 'Agency\RoleController@index'));
    Route::get('/agency/roles/add', array('as' => 'roles_add', 'uses' => 'Agency\RoleController@add'));
    Route::post('/agency/roles/add', array('as' => 'roles_store', 'uses' => 'Agency\RoleController@store'));
    Route::get('/agency/roles/{id}', array('as' => 'roles_edit', 'uses' => 'Agency\RoleController@edit'));
    Route::post('/agency/roles', array('as' => 'roles_update', 'uses' => 'Agency\RoleController@update'));
    Route::get('/agency/roles/delete/{id}', array('as' => 'roles_delete', 'uses' => 'Agency\RoleController@delete'));

    Route::get('/agency/projects', array('as' => 'projects_index', 'uses' => 'Agency\ProjectController@index'));
    Route::get('/agency/projects/add', array('as' => 'projects_add', 'uses' => 'Agency\ProjectController@add'));
    Route::post('/agency/projects/add', array('as' => 'projects_store', 'uses' => 'Agency\ProjectController@store'));
    Route::get('/agency/projects/{uuid}', array('as' => 'projects_edit', 'uses' => 'Agency\ProjectController@edit'));
    Route::get('/agency/projects/{uuid}/team', array('as' => 'projects_edit_team', 'uses' => 'Agency\ProjectController@edit_team'));
    Route::get('/agency/projects/{uuid}/tasks', array('as' => 'projects_edit_tasks', 'uses' => 'Agency\ProjectController@edit_tasks'));
    Route::get('/agency/projects/{uuid}/attribution', array('as' => 'projects_edit_attribution', 'uses' => 'Agency\ProjectController@edit_attribution'));
    Route::get('/agency/projects/{uuid}/config', array('as' => 'projects_edit_config', 'uses' => 'Agency\ProjectController@edit_config'));
    Route::post('/agency/projects', array('as' => 'projects_update', 'uses' => 'Agency\ProjectController@update'));
    Route::post('/agency/projects/config', array('as' => 'projects_update_config', 'uses' => 'Agency\ProjectController@update_config'));
    Route::get('/agency/projects/delete/{uuid}', array('as' => 'projects_delete', 'uses' => 'Agency\ProjectController@delete'));
    Route::post('/agency/projects/add_user', array('as' => 'projects_add_user', 'uses' => 'Agency\ProjectController@add_user'));
    Route::get('/agency/projects/delete_user/{uuid}/{user_id}', array('as' => 'projects_delete_user', 'uses' => 'Agency\ProjectController@delete_user'));
    Route::post('/agency/projects/tasks', array('as' => 'projects_update_tasks', 'uses' => 'Agency\ProjectController@update_tasks'));
    Route::post('/agency/projects/allocate_task_in_planning', array('as' => 'projects_allocate_task_in_planning', 'uses' => 'Agency\ProjectController@allocate_task_in_planning'));

    Route::get('/agency/clients', array('as' => 'clients_index', 'uses' => 'Agency\ClientController@index'));
    Route::get('/agency/clients/add', array('as' => 'clients_add', 'uses' => 'Agency\ClientController@add'));
    Route::post('/agency/clients/add', array('as' => 'clients_store', 'uses' => 'Agency\ClientController@store'));
    Route::get('/agency/clients/{uuid}', array('as' => 'clients_edit', 'uses' => 'Agency\ClientController@edit'));
    Route::post('/agency/clients', array('as' => 'clients_update', 'uses' => 'Agency\ClientController@update'));
    Route::get('/agency/clients/delete/{uuid}', array('as' => 'clients_delete', 'uses' => 'Agency\ClientController@delete'));

    Route::get('/agency/clients/{uuid}/add_user', array('as' => 'clients_add_user', 'uses' => 'Agency\ClientController@add_user'));
    Route::post('/agency/clients/store_user', array('as' => 'clients_store_user', 'uses' => 'Agency\ClientController@store_user'));
    Route::get('/agency/clients/{uuid}/edit_user/{user_id}', array('as' => 'clients_edit_user', 'uses' => 'Agency\ClientController@edit_user'));
    Route::post('/agency/clients/update_user', array('as' => 'clients_update_user', 'uses' => 'Agency\ClientController@update_user'));
    Route::get('/agency/clients/{uuid}/delete_user/{user_id}', array('as' => 'clients_delete_user', 'uses' => 'Agency\ClientController@delete_user'));

    Route::get('/agency/users', array('as' => 'users_index', 'uses' => 'Agency\UserController@index'));
    Route::get('/agency/users/add', array('as' => 'users_add', 'uses' => 'Agency\UserController@add'));
    Route::post('/agency/users/add', array('as' => 'users_store', 'uses' => 'Agency\UserController@store'));
    Route::get('/agency/users/{uuid}', array('as' => 'users_edit', 'uses' => 'Agency\UserController@edit'));
    Route::post('/agency/users', array('as' => 'users_update', 'uses' => 'Agency\UserController@update'));
    Route::get('/agency/users/delete/{uuid}', array('as' => 'users_delete', 'uses' => 'Agency\UserController@delete'));
    Route::get('/agency/user_generate_new_password/{uuid}', array('as' => 'users_generate_password', 'uses' => 'Agency\UserController@generate_password'));

    Route::get('/ticket_types', array('as' => 'ticket_types_index', 'uses' => 'Agency\TicketTypeController@index'));
    Route::get('/ticket_types/add', array('as' => 'ticket_types_add', 'uses' => 'Agency\TicketTypeController@add'));
    Route::post('/ticket_types/add', array('as' => 'ticket_types_store', 'uses' => 'Agency\TicketTypeController@store'));
    Route::get('/ticket_types/{id}', array('as' => 'ticket_types_edit', 'uses' => 'Agency\TicketTypeController@edit'));
    Route::post('/ticket_types', array('as' => 'ticket_types_update', 'uses' => 'Agency\TicketTypeController@update'));
    Route::get('/ticket_types/delete/{id}', array('as' => 'ticket_types_delete', 'uses' => 'Agency\TicketTypeController@delete'));

    Route::get('/ticket_statuses', array('as' => 'ticket_statuses_index', 'uses' => 'Agency\TicketStatusController@index'));
    Route::get('/ticket_statuses/add', array('as' => 'ticket_statuses_add', 'uses' => 'Agency\TicketStatusController@add'));
    Route::post('/ticket_statuses/add', array('as' => 'ticket_statuses_store', 'uses' => 'Agency\TicketStatusController@store'));
    Route::get('/ticket_statuses/{id}', array('as' => 'ticket_statuses_edit', 'uses' => 'Agency\TicketStatusController@edit'));
    Route::post('/ticket_statuses', array('as' => 'ticket_statuses_update', 'uses' => 'Agency\TicketStatusController@update'));
    Route::get('/ticket_statuses/delete/{id}', array('as' => 'ticket_statuses_delete', 'uses' => 'Agency\TicketStatusController@delete'));

    //MESSAGES
    Route::get('/conversations', array('as' => 'conversations_index', 'uses' => 'Utility\MessageController@index'));
    Route::post('/conversations/reply', array('as' => 'conversations_reply', 'uses' => 'Utility\MessageController@reply'));
    Route::get('/conversations/{id}', array('as' => 'conversations_view', 'uses' => 'Utility\MessageController@view'));
    Route::post('/conversations', array('as' => 'add_conversation', 'uses' => 'Utility\MessageController@addConversation'));

    //MONITORING
    Route::get('/monitoring', array('as' => 'monitoring_index', 'uses' => 'Utility\MonitoringController@index'));

    //PLANNING
    Route::get('/planning', array('as' => 'planning', 'uses' => 'Utility\PlanningController@index'));
    Route::get('/planning/get_event', array('as' => 'events_get_infos', 'uses' => 'Utility\PlanningController@get_event'));
    Route::post('/planning/create', array('as' => 'events_create', 'uses' => 'Utility\PlanningController@create'));
    Route::post('/planning/update', array('as' => 'events_update', 'uses' => 'Utility\PlanningController@update'));
    Route::post('/planning/delete', array('as' => 'events_delete', 'uses' => 'Utility\PlanningController@delete'));

    //OCCUPATION
    Route::get('/occupation', array('as' => 'occupation', 'uses' => 'Utility\OccupationController@index'));

    //CONFIG
    Route::get('/config', array('as' => 'config', 'uses' => 'ConfigController@index', 'middleware' => 'after_config'));
    Route::post('/config', array('as' => 'config_handler', 'uses' => 'ConfigController@config_handler', 'middleware' => 'after_config'));
    Route::get('/config_confirmation', array('as' => 'config_confirmation', 'uses' => 'ConfigController@confirmation'));

    //MY
    Route::get('/my', array('as' => 'my', 'uses' => 'MyController@index'));
    Route::post('/my/udpate_profile', array('as' => 'my_profile_update', 'uses' => 'MyController@udpate_profile'));
    Route::post('/my/upload_avatar', array('as' => 'my_profile_upload_avatar', 'uses' => 'MyController@upload_avatar'));
    Route::post('/my/update_notifications', array('as' => 'my_profile_update_notifications', 'uses' => 'MyController@update_notifications'));

    //BETA FORM
    Route::post('/beta_form', array('as' => 'beta_form', 'uses' => 'BaseController@betaForm'));

    Route::get('/project_users', array('as' => 'project_users', 'uses' => 'ProjectController@get_users'));

    //SETTINGS
    Route::get('/settings', array('as' => 'settings_index', 'uses' => 'Agency\SettingsController@index'));
    Route::post('/settings', array('as' => 'settings_update', 'uses' => 'Agency\SettingsController@update'));
});