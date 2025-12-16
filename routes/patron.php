<?php
Route::middleware(['auth','verified'])
->namespace('Patron')
->name('patron.')->prefix('patron/')->group(function () {

    Route::name('section.')
    ->prefix('/section')
    ->group(function (){
       Route::get('/{sectionId}', 'SectionController@index')->name('index');

        Route::name('performance.')
        ->prefix('/performance')
        ->group(function (){
            Route::get('/{sectioClassId}', 'PerformanceController@index')->name('index');
        });
    });

    Route::name('analysis.')
        ->prefix('/analysis')
        ->group(function (){
        Route::get('/teaching', 'AnalysisController@teaching')->name('teaching');
    });

});