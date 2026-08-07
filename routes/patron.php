<?php
Route::middleware(['auth','verified', 'password'])
->namespace('Patron')
->name('patron.')->prefix('patron/')->group(function () {

    Route::name('analysis.')
        ->prefix('/analysis')
        ->group(function (){
        Route::get('/', 'AnalysisController@index')->name('index');
        Route::post('/search', 'AnalysisController@search')->name('search');
        Route::get('/view', 'AnalysisController@view')->name('view');
    });

    Route::name('statistics.')
        ->prefix('/statistics')
        ->group(function () {
            Route::view('/students', 'patron.statistics.students')->name('students');
            Route::view('/teachers', 'patron.statistics.teachers')->name('teachers');
        });

});