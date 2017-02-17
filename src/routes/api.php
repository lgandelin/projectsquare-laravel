<?php

Route::group(['middleware' => 'api', 'namespace' => 'Webaccess\ProjectSquareLaravel\Http\Controllers'], function () {

    Route::get('/api/users_count', array('as' => 'users_count', 'uses' => 'APIController@users_count'));
    Route::post('/api/update_users_count', array('as' => 'udpate_users_count', 'uses' => 'APIController@update_users_count'));
});