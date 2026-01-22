<?php
Route::middleware(['auth','verified','admin', 'password'])
->namespace('Admin')
->name('admin.')->prefix('admin/')->group(function () {
    
    Route::name('user.')
    ->prefix('/user')
    ->group(function (){
        Route::get('/{type}', 'UserController@index')->name('index');
        Route::put('/{userId}', 'UserController@update')->name('update');

    });
});