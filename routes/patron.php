<?php
Route::middleware(['auth','verified'])
->namespace('Patron')
->name('patron.')->prefix('patron/')->group(function () {

    Route::name('analysis.')
        ->prefix('/analysis')
        ->group(function (){
        Route::get('/', 'AnalysisController@index')->name('index');
        Route::post('/search', 'AnalysisController@search')->name('search');
        Route::get('/view', 'AnalysisController@view')->name('view');
    });

});