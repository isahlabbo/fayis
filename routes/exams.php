<?php
Route::middleware(['auth','verified','exam'])
->namespace('Examination')
->name('exam.')->prefix('exam/')->group(function () {
    
    Route::name('upload.')
    ->prefix('/upload')
    ->group(function (){
        Route::get('/{sectionId}', 'UploadController@index')->name('index');
        Route::get('/{sectionId}/summary', 'UploadController@summary')->name('summary');
    });
});