<?php
Route::middleware(['auth','verified','admin', 'password'])
->namespace('Admin')
->name('admin.')->prefix('admin/')->group(function () {

    Route::get('/login-as/{user}', 'UserController@loginAs')->name('login-as');

    Route::name('user.')
    ->prefix('/user')
    ->group(function (){
        Route::get('/{type}', 'UserController@index')->name('index');
        Route::put('/{userId}', 'UserController@update')->name('update');

    });

    Route::name('card.')
    ->prefix('/card-request')
    ->group(function (){
        Route::get('/', 'CardRequestController@index')->name('index');
        Route::get('/{user}', 'CardRequestController@view')->name('view');
        Route::put('/{cardRequest}', 'CardRequestController@markAsPrinted')->name('markAsPrinted');
        Route::post('/{cardRequest}/update', 'CardRequestController@update')->name('update');
        Route::get('/{cardRequest}/delete', 'CardRequestController@delete')->name('delete');

    });
});