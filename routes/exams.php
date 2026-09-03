<?php
Route::middleware(['auth', 'verified', 'password', 'permission:view-student-results'])
    ->namespace('Examination')
    ->name('exam.student-results.')
    ->prefix('exam/student-results')
    ->group(function () {
        Route::get('/', 'StudentResultController@index')->name('index');
        Route::get('/{studentTerm}/audit', 'StudentResultController@audit')->name('audit');
        Route::get('/{studentTerm}/report-card', 'StudentResultController@download')->name('download');
    });

Route::middleware(['auth', 'verified', 'password', 'permission:view-result-access-codes'])
    ->namespace('Examination')
    ->group(function () {
        Route::get('exam/result-access-codes', 'ResultController@accessCodes')
            ->name('exam.result-access-codes.index');
        Route::post('exam/result-access-codes/generate', 'ResultController@generateAccessCodes')
            ->name('exam.result-access-codes.generate');
    });

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
