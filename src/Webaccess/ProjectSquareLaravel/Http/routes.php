<?php

Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::group(['middleware' => ['web']], function () {

    //DASHBOARD
    Route::get('/', array('as' => 'dashboard', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\DashboardController@index'));
    Route::get('/login', array('as' => 'login', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@login'));
    Route::post('/login', array('as' => 'login_handler', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@authenticate'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@logout'));
    Route::get('/forgotten_password', array('as' => 'forgotten_password', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@forgotten_password'));
    Route::post('/forgotten_password_handler', array('as' => 'forgotten_password_handler', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@forgotten_password_handler'));

    //NOTIFICATIONS
    Route::get('/get_notifications', array('as' => 'get_notifications', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\NotificationController@get_notifications'));
    Route::post('/read_notification', array('as' => 'read_notification', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\NotificationController@read'));

    //TODOS
    Route::get('/todos', array('as' => 'todos_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TodoController@index'));
    Route::post('/todos/create', array('as' => 'todos_create', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TodoController@create'));
    Route::post('/todos/update', array('as' => 'todos_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TodoController@update'));
    Route::post('/todos/delete', array('as' => 'todos_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TodoController@delete'));

    //TICKETS
    Route::get('/tickets', array('as' => 'tickets_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@index'));
    Route::get('/tickets/add', array('as' => 'tickets_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@add'));
    Route::post('/tickets/add', array('as' => 'tickets_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@store'));
    Route::get('/tickets/{uuid}', array('as' => 'tickets_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@edit'));
    Route::post('/tickets/upload_file', array('as' => 'tickets_edit_upload_file', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@upload_file'));
    Route::get('/tickets/delete_file/{id}', array('as' => 'tickets_edit_delete_file', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@delete_file'));
    Route::post('/tickets', array('as' => 'tickets_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@update'));
    Route::post('/tickets_infos', array('as' => 'tickets_update_infos', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@updateInfos'));
    Route::post('/tickets_unallocate', array('as' => 'tickets_unallocate', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@unallocate'));
    Route::get('/delete_ticket/{uuid}', array('as' => 'tickets_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TicketController@delete'));

    //TASKS
    Route::get('/tasks', array('as' => 'tasks_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@index'));
    Route::get('/tasks/add', array('as' => 'tasks_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@add'));
    Route::post('/tasks/add', array('as' => 'tasks_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@store'));
    Route::get('/tasks/{uuid}', array('as' => 'tasks_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@edit'));
    Route::post('/tasks/update', array('as' => 'tasks_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@update'));
    Route::post('/tasks_unallocate', array('as' => 'tasks_unallocate', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@unallocate'));
    Route::get('/delete_task/{uuid}', array('as' => 'tasks_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\TaskController@delete'));

    //PROJECTS
    Route::get('/project/{uuid}', array('as' => 'project_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@index', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/tasks', array('as' => 'project_tasks', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@tasks', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/tickets', array('as' => 'project_tickets', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@tickets', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/monitoring', array('as' => 'project_monitoring', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@monitoring', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/project_seo', array('as' => 'project_seo', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@seo', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/messages', array('as' => 'project_messages', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@messages', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/calendar', array('as' => 'project_calendar', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\CalendarController@index', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/reporting', array('as' => 'project_reporting', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@reporting', 'middleware' => 'change_current_project'));
    Route::get('/project/{uuid}/files', array('as' => 'project_files', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\FilesController@index', 'middleware' => 'change_current_project'));

    //PROJECT FILES
    Route::post('/project/files/upload', array('as' => 'projects_files_upload', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\FilesController@upload'));
    Route::get('/project/files/delete/{id}', array('as' => 'projects_files_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\FilesController@delete'));

    //PROJECT CALENDAR
    Route::get('/calendar/get_step', array('as' => 'steps_get_infos', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\CalendarController@get_step'));
    Route::post('/calendar/create', array('as' => 'steps_create', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\CalendarController@create'));
    Route::post('/calendar/update', array('as' => 'steps_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\CalendarController@update'));
    Route::post('/calendar/delete', array('as' => 'steps_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Project\CalendarController@delete'));

    //AGENCY
    Route::get('/agency/users', array('as' => 'users_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@index'));
    Route::get('/agency/roles', array('as' => 'roles_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@index'));
    Route::get('/agency/roles/add', array('as' => 'roles_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@add'));
    Route::post('/agency/roles/add', array('as' => 'roles_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@store'));
    Route::get('/agency/roles/{id}', array('as' => 'roles_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@edit'));
    Route::post('/agency/roles', array('as' => 'roles_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@update'));
    Route::get('/agency/roles/delete/{id}', array('as' => 'roles_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@delete'));

    Route::get('/agency/projects', array('as' => 'projects_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@index'));
    Route::get('/agency/projects/add', array('as' => 'projects_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@add'));
    Route::post('/agency/projects/add', array('as' => 'projects_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@store'));
    Route::get('/agency/projects/{uuid}', array('as' => 'projects_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@edit'));
    Route::post('/agency/projects', array('as' => 'projects_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@update'));
    Route::get('/agency/projects/delete/{uuid}', array('as' => 'projects_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@delete'));
    Route::post('/agency/projects/add_user', array('as' => 'projects_add_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@add_user'));
    Route::get('/agency/projects/delete_user/{uuid}/{user_id}', array('as' => 'projects_delete_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@delete_user'));
    Route::post('/agency/projects/{uuid}/settings', array('as' => 'project_settings', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@update_settings'));

    Route::get('/agency/clients', array('as' => 'clients_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@index'));
    Route::get('/agency/clients/add', array('as' => 'clients_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@add'));
    Route::post('/agency/clients/add', array('as' => 'clients_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@store'));
    Route::get('/agency/clients/{uuid}', array('as' => 'clients_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@edit'));
    Route::post('/agency/clients', array('as' => 'clients_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@update'));
    Route::get('/agency/clients/delete/{uuid}', array('as' => 'clients_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@delete'));

    Route::get('/agency/clients/{uuid}/add_user', array('as' => 'clients_add_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@add_user'));
    Route::post('/agency/clients/store_user', array('as' => 'clients_store_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@store_user'));
    Route::get('/agency/clients/{uuid}/edit_user/{user_id}', array('as' => 'clients_edit_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@edit_user'));
    Route::post('/agency/clients/update_user', array('as' => 'clients_update_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@update_user'));
    Route::get('/agency/clients/{uuid}/delete_user/{user_id}', array('as' => 'clients_delete_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@delete_user'));

    Route::get('/agency/users', array('as' => 'users_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@index'));
    Route::get('/agency/users/add', array('as' => 'users_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@add'));
    Route::post('/agency/users/add', array('as' => 'users_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@store'));
    Route::get('/agency/users/{uuid}', array('as' => 'users_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@edit'));
    Route::post('/agency/users', array('as' => 'users_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@update'));
    Route::get('/agency/users/delete/{uuid}', array('as' => 'users_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@delete'));
    Route::get('/agency/user_generate_new_password/{uuid}', array('as' => 'users_generate_password', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@generate_password'));

    Route::get('/ticket_types', array('as' => 'ticket_types_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@index'));
    Route::get('/ticket_types/add', array('as' => 'ticket_types_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@add'));
    Route::post('/ticket_types/add', array('as' => 'ticket_types_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@store'));
    Route::get('/ticket_types/{id}', array('as' => 'ticket_types_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@edit'));
    Route::post('/ticket_types', array('as' => 'ticket_types_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@update'));
    Route::get('/ticket_types/delete/{id}', array('as' => 'ticket_types_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@delete'));

    Route::get('/ticket_statuses', array('as' => 'ticket_statuses_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@index'));
    Route::get('/ticket_statuses/add', array('as' => 'ticket_statuses_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@add'));
    Route::post('/ticket_statuses/add', array('as' => 'ticket_statuses_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@store'));
    Route::get('/ticket_statuses/{id}', array('as' => 'ticket_statuses_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@edit'));
    Route::post('/ticket_statuses', array('as' => 'ticket_statuses_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@update'));
    Route::get('/ticket_statuses/delete/{id}', array('as' => 'ticket_statuses_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@delete'));

    //MESSAGES
    Route::get('/conversations', array('as' => 'conversations_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\MessageController@index'));
    Route::post('/conversations/reply', array('as' => 'conversations_reply', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\MessageController@reply'));
    Route::get('/conversations/{id}', array('as' => 'conversations_view', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\MessageController@view'));
    Route::post('/conversations', array('as' => 'add_conversation', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\MessageController@addConversation'));

    //MONITORING
    Route::get('/monitoring', array('as' => 'monitoring_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\MonitoringController@index'));

    //PLANNING
    Route::get('/planning', array('as' => 'planning', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\PlanningController@index'));
    Route::get('/planning/get_event', array('as' => 'events_get_infos', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\PlanningController@get_event'));
    Route::post('/planning/create', array('as' => 'events_create', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\PlanningController@create'));
    Route::post('/planning/update', array('as' => 'events_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\PlanningController@update'));
    Route::post('/planning/delete', array('as' => 'events_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\PlanningController@delete'));

    //CONFIG
    Route::get('/config', array('as' => 'config', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ConfigController@index', 'middleware' => 'after_config'));
    Route::post('/config', array('as' => 'config_handler', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ConfigController@config_handler', 'middleware' => 'after_config'));
    Route::get('/config_confirmation', array('as' => 'config_confirmation', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ConfigController@confirmation'));

    //MY
    Route::get('/my', array('as' => 'my', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MyController@index'));
    Route::post('/my/profile_update', array('as' => 'my_profile_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MyController@udpate_profile'));
    Route::post('/my/profile_upload_avatar', array('as' => 'my_profile_upload_avatar', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MyController@upload_avatar'));

    //BETA FORM
    Route::post('/beta_form', array('as' => 'beta_form', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController@betaForm'));

    Route::get('/project_users', array('as' => 'project_users', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@get_users'));

    //SETTINGS
    Route::get('/settings', array('as' => 'settings_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\SettingsController@index'));
    Route::post('/settings', array('as' => 'settings_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\SettingsController@update'));
});

Route::group(['middleware' => ['api']], function () {

    //API
    Route::get('/api/users_count', array('as' => 'users_count', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\APIController@users_count'));
    Route::post('/api/update_users_count', array('as' => 'udpate_users_count', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\APIController@update_users_count'));
});