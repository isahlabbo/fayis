<?php
Route::middleware(['auth','verified','exam', 'password'])
->namespace('Examination')
->name('exam.')->prefix('exam/')->group(function () {
    
    Route::name('upload.')
    ->prefix('/upload')
    ->group(function (){
        Route::get('/report', 'UploadController@report')->name('report');
        Route::get('/classes', 'UploadController@classReport')->name('class.report');
        Route::get('/{sectionClassId}/shows', 'UploadController@classReportShow')->name('class.report.show');
        
        Route::get('/{sectionId}', 'UploadController@index')->name('index');
        Route::get('/{sectionId}/summary', 'UploadController@summary')->name('summary');
        Route::get('/{uploadId}/details', 'UploadController@details')->name('details');
        Route::get('/{uploadId}/return-for-correction', 'UploadController@ReturnForCorrection')->name('edit');
        
        Route::name('teacher.')
        ->prefix('/teacher')
        ->group(function (){
            Route::get('/{teacher}', 'TeacherUploadController@index')->name('index');
            Route::post('/{uploadId}', 'TeacherUploadController@update')->name('update');
            Route::get('/{uploadId}/delete', 'TeacherUploadController@delete')->name('delete');
            
        });

        Route::name('result.')
        ->prefix('/result')
        ->group(function (){
            Route::put('/{studentResultId}/update', 'ResultController@update')->name('update');
            Route::get('/{sectionClassId}/publish', 'ResultController@publish')->name('publish');
            Route::get('/{sectionId}/access-code', 'ResultController@accessCode')->name('accessCode');
        });
    });
});