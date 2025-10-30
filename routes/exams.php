<?php
Route::middleware(['auth','verified','exam'])
->namespace('Examination')
->name('exam.')->prefix('exam/')->group(function () {
    
    Route::name('upload.')
    ->prefix('/upload')
    ->group(function (){
        Route::get('/{sectionId}', 'UploadController@index')->name('index');
        Route::get('/{sectionId}/summary', 'UploadController@summary')->name('summary');
        Route::get('/{uploadId}/details', 'UploadController@details')->name('details');
        Route::get('/{uploadId}/return-for-correction', 'UploadController@ReturnForCorrection')->name('edit');
        
        Route::name('result.')
        ->prefix('/result')
        ->group(function (){
            Route::put('/{studentResultId}/update', 'ResultController@update')->name('update');
            Route::get('/{sectionClassId}/publish', 'ResultController@publish')->name('publish');
        });
    });
});