<?php

/*
|--------------------------------------------------------------------------
| Sheeraz\LogManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Sheeraz\LogManager package.
|
*/

Route::group([
    'namespace'  => 'Sheeraz\LogManager\app\Http\Controllers',
    'middleware' => ['web', config('sheeraz.base.middleware_key', 'admin')],
    'prefix'     => config('sheeraz.base.route_prefix', 'admin'),
], function () {
    Route::get('log', 'LogController@index')->name('log.index');
    Route::get('log/preview/{file_name}', 'LogController@preview')->name('log.show');
    Route::get('log/download/{file_name}', 'LogController@download')->name('log.download');
    Route::get('log/mail/{file_name}', 'LogController@mail')->name('log.mail');
    Route::get('log/mail_log_to_user/{file_name}/{user_id}', 'LogController@mail_log_to_user')->name('log.mail_user');
    Route::delete('log/delete/{file_name}', 'LogController@delete')->name('log.destroy');
});
