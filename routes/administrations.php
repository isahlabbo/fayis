<?php

Route::middleware(['auth','verified', 'password'])->name('administration.')->group(function () {
    Route::namespace('School')
    ->name('session.')
    ->prefix('/session')
    ->group(function (){
        Route::get('/', 'AcademicSessionController@index')->name('index');
        Route::get('/{academicSessionId}/activate', 'AcademicSessionController@saveAsCurrentSession')->name('activate');
        Route::get('/{academicSessionId}/configure', 'AcademicSessionController@configure')->name('configure');
        Route::post('/{academicSessionId}/update', 'AcademicSessionController@updateSession')->name('update');
        Route::post('/configuration/termly/update', 'AcademicSessionController@updateTermlyCalendar')->name('configuration.term.update');
    });

    Route::name('card.')
    ->prefix('/card')
    ->group(function (){
        Route::get('/', 'IDCardController@index')->name('index');
        Route::get('/{requestId}/approve', 'IDCardController@approve')->name('approve');
        Route::get('/{requestId}/delete', 'IDCardController@delete')->name('delete');
        Route::put('/{cardRequestId}/update', 'IDCardController@update')->name('update');
        Route::post('/register', 'IDCardController@register')->name('register');
    });

    Route::namespace('School')
    ->name('teacher.')
    ->prefix('/teacher')
    ->group(function (){
        // score sheet route
        Route::get('/{sectionClassSubjecTeacherId}/download-score', 'ScoreSheetController@download')
        ->name('download.scoresheet');
        Route::get('/{sectionClassSubjecTeacherId}/download-record-sheet', 'ScoreSheetController@downloadRecordSheet')
        ->name('download.recordsheet');

        Route::get('/{sectionClassSubjecId}/upload-score', 'ScoreSheetController@upload')
        ->name('upload.scoresheet');

        Route::post('/{sectionClassSubjecId}/upload-now', 'ScoreSheetController@uploadNow')
        ->name('scoresheet.uploadNow');

        Route::get('/', 'TeacherController@index')->name('index');
        Route::get('/create', 'TeacherController@create')->name('create');
        Route::post('/register', 'TeacherController@register')->name('register');
        Route::post('/{teacherId}/update', 'TeacherController@update')->name('update');
        Route::get('/{teacherId}/delete', 'TeacherController@delete')->name('delete');
        // 
        Route::name('subject.')
            ->prefix('/{teacherId}/subject')
            ->group(function (){
                Route::get('/', 'TeacherSubjectController@index')->name('index');
                Route::post('/add', 'TeacherSubjectController@add')->name('add');
                Route::get('/{subjectId}/remove', 'TeacherSubjectController@remove')->name('remove');
        });
        // school.teacher.evaluation
        Route::name('evaluation.')
        ->prefix('/evaluation')
        ->group(function (){
            Route::get('/session/{sessionId}', 'TeachingEvaluationController@index')->name('index');
        });
    });

    Route::name('user.')
    ->prefix('/user')
    ->group(function (){
        Route::get('/', 'UserController@index')->name('index');
        Route::post('/register', 'UserController@register')->name('register');
        Route::post('/{userId}/update', 'UserController@update')->name('update');
    });

    Route::name('staff.')
    ->prefix('/staff')
    ->group(function (){
        Route::get('/', 'StaffController@index')->name('index');
        Route::post('/register', 'StaffController@register')->name('register');
        Route::post('/{staffId}/update', 'StaffController@update')->name('update');
        Route::get('/{staffId}/delete', 'StaffController@delete')->name('delete');
    });
});