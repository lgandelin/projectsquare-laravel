<?php

Route::group(['middleware' => ['web']], function () {

    //DASHBOARD
    Route::get('/', array('as' => 'dashboard', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\DashboardController@index'));
    Route::get('/login', array('as' => 'login', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@login'));
    Route::post('/login', array('as' => 'login_handler', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@authenticate'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@logout'));
    Route::get('/forgotten_password', array('as' => 'forgotten_password', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@forgotten_password'));
    Route::post('/forgotten_password_handler', array('as' => 'forgotten_password_handler', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\LoginController@forgotten_password_handler'));

    Route::get('/planning', array('as' => 'planning', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\EventsController@index'));

    //NOTIFICATIONS
    Route::get('/get_notifications', array('as' => 'get_notifications', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\NotificationController@get_notifications'));
    Route::post('/read_notification', array('as' => 'read_notification', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\NotificationController@read'));

    //TASKS
    Route::get('/tasks', array('as' => 'tasks_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TaskController@index'));
    Route::post('/tasks/create', array('as' => 'tasks_create', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TaskController@create'));
    Route::post('/tasks/update', array('as' => 'tasks_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TaskController@update'));
    Route::post('/tasks/delete', array('as' => 'tasks_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TaskController@delete'));

    //TICKETS
    Route::get('/tickets', array('as' => 'tickets_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@index'));
    Route::get('/add_ticket', array('as' => 'tickets_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@add'));
    Route::post('/add_ticket', array('as' => 'tickets_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@store'));
    Route::get('/tickets/{id}', array('as' => 'tickets_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@edit'));
    Route::post('/tickets/upload_file', array('as' => 'tickets_edit_upload_file', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@upload_file'));
    Route::get('/tickets/delete_file/{id}', array('as' => 'tickets_edit_delete_file', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@delete_file'));
    Route::post('/tickets', array('as' => 'tickets_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@update'));
    Route::post('/tickets_infos', array('as' => 'tickets_update_infos', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@updateInfos'));
    Route::get('/delete_ticket/{id}', array('as' => 'tickets_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@delete'));

    //PROJECTS
    Route::get('/project/{id}', array('as' => 'project_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@index', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/cms', array('as' => 'project_cms', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@cms', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/tickets', array('as' => 'project_tickets', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@tickets', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/monitoring', array('as' => 'project_monitoring', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@monitoring', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/calendar', array('as' => 'project_calendar', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@calendar', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/settings', array('as' => 'project_settings', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@settings', 'middleware' => 'change_current_project'));
    Route::post('/project/{id}/settings', array('as' => 'project_settings', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@update_settings'));
    Route::get('/project/{id}/project_seo', array('as' => 'project_seo', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@seo', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/messages', array('as' => 'project_messages', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@messages', 'middleware' => 'change_current_project'));
    Route::get('/project/{id}/files', array('as' => 'project_files', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@files', 'middleware' => 'change_current_project'));
    Route::post('/project/files/upload', array('as' => 'projects_files_upload', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@upload_file'));
    Route::get('/project/files/delete/{id}', array('as' => 'projects_files_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@delete_file'));


    //AGENCY
    Route::get('/agency/users', array('as' => 'users_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@index'));
    Route::get('/agency/roles', array('as' => 'roles_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@index'));
    Route::get('/agency/add_role', array('as' => 'roles_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@add'));
    Route::post('/agency/add_role', array('as' => 'roles_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@store'));
    Route::get('/agency/roles/{id}', array('as' => 'roles_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@edit'));
    Route::post('/agency/roles', array('as' => 'roles_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@update'));
    Route::get('/agency/delete_role/{id}', array('as' => 'roles_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\RoleController@delete'));

    Route::get('/agency/projects', array('as' => 'projects_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@index'));
    Route::get('/agency/add_project', array('as' => 'projects_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@add'));
    Route::post('/agency/add_project', array('as' => 'projects_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@store'));
    Route::get('/agency/projects/{id}', array('as' => 'projects_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@edit'));
    Route::post('/agency/projects', array('as' => 'projects_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@update'));
    Route::get('/agency/delete_project/{id}', array('as' => 'projects_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@delete'));
    Route::post('/agency/project_add_user', array('as' => 'projects_add_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@add_user'));
    Route::get('/agency/projects_delete_user/{id}/{user_id}', array('as' => 'projects_delete_user', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ProjectController@delete_user'));

    Route::get('/agency/clients', array('as' => 'clients_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@index'));
    Route::get('/agency/add_client', array('as' => 'clients_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@add'));
    Route::post('/agency/add_client', array('as' => 'clients_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@store'));
    Route::get('/agency/clients/{id}', array('as' => 'clients_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@edit'));
    Route::post('/agency/clients', array('as' => 'clients_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@update'));
    Route::get('/agency/delete_client/{id}', array('as' => 'clients_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\ClientController@delete'));

    Route::get('/agency/users', array('as' => 'users_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@index'));
    Route::get('/agency/add_user', array('as' => 'users_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@add'));
    Route::post('/agency/add_user', array('as' => 'users_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@store'));
    Route::get('/agency/users/{id}', array('as' => 'users_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@edit'));
    Route::post('/agency/users', array('as' => 'users_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@update'));
    Route::get('/agency/delete_user/{id}', array('as' => 'users_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\UserController@delete'));

    Route::get('/ticket_types', array('as' => 'ticket_types_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@index'));
    Route::get('/add_ticket_type', array('as' => 'ticket_types_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@add'));
    Route::post('/add_ticket_type', array('as' => 'ticket_types_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@store'));
    Route::get('/ticket_types/{id}', array('as' => 'ticket_types_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@edit'));
    Route::post('/ticket_types', array('as' => 'ticket_types_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@update'));
    Route::get('/delete_ticket_type/{id}', array('as' => 'ticket_types_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketTypeController@delete'));

    Route::get('/ticket_statuses', array('as' => 'ticket_statuses_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@index'));
    Route::get('/add_ticket_status', array('as' => 'ticket_statuses_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@add'));
    Route::post('/add_ticket_status', array('as' => 'ticket_statuses_store', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@store'));
    Route::get('/ticket_statuses/{id}', array('as' => 'ticket_statuses_edit', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@edit'));
    Route::post('/ticket_statuses', array('as' => 'ticket_statuses_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@update'));
    Route::get('/delete_ticket_status/{id}', array('as' => 'ticket_statuses_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\Agency\TicketStatusController@delete'));

    //MESSAGES
    Route::get('/messages', array('as' => 'messages_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MessageController@index'));
    Route::get('/messages/add_message', array('as' => 'messages_add', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MessageController@add'));
    Route::post('/messages/reply', array('as' => 'messages_reply', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MessageController@reply'));

    Route::get('/conversations/{id}', array('as' => 'conversation', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MessageController@view'));
    Route::post('/conversations', array('as' => 'add_conversation', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\MessageController@addConversation'));

    //MONITORING
    Route::get('/monitoring', array('as' => 'monitoring_index', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@index'));

    //PLANNING
    Route::get('/planning/get_event', array('as' => 'events_get_infos', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\EventsController@get_event'));
    Route::post('/planning/create', array('as' => 'events_create', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\EventsController@create'));
    Route::post('/planning/update', array('as' => 'events_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\EventsController@update'));
    Route::post('/planning/delete', array('as' => 'events_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\EventsController@delete'));

    //CALENDAR
    Route::get('/calendar/get_step', array('as' => 'steps_get_infos', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\StepsController@get_step'));
    Route::post('/calendar/create', array('as' => 'steps_create', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\StepsController@create'));
    Route::post('/calendar/update', array('as' => 'steps_update', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\StepsController@update'));
    Route::post('/calendar/delete', array('as' => 'steps_delete', 'uses' => 'Webaccess\ProjectSquareLaravel\Http\Controllers\StepsController@delete'));
});