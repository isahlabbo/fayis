<?php

Route::middleware(['auth','verified','teacher'])
->namespace('Teacher')
->name('teacher.')->prefix('teacher/')->group(function () {
    
    Route::name('class.')
    ->prefix('/class')
    ->group(function (){
        Route::get('{classId}/', 'ClassController@index')->name('index');
        Route::get('{classId}/students', 'ClassController@students')->name('students');
        Route::get('{classId}/report-cards', 'ClassController@reportCards')->name('reportCards');
    //    assessment routes
        Route::name('assessment.')
        ->prefix('/assessment')
        ->group(function (){
            Route::get('{classTeacherId}/', 'AssessmentController@index')->name('index');
            Route::get('{studenTermId}/edit', 'AssessmentController@edit')->name('edit');
            Route::post('{studentClassId}', 'AssessmentController@update')->name('update');
        });
        Route::name('attendance.')
        ->prefix('/attendance')
        ->group(function (){
            Route::get('{classTeacherId}/', 'AttendanceController@index')->name('index');
            Route::get('{studenTermId}/edit', 'AttendanceController@edit')->name('edit');
            Route::post('{studentClassId}', 'AttendanceController@update')->name('update');
        });
    });

    Route::name('subject.')
    ->prefix('/subject')
    ->group(function (){
        Route::get('/{subjectTeacherId}', 'SubjectTeacherController@index')->name('index');
        // routes first ca 
        Route::name('firstca.')
        ->prefix('/first-ca')
        ->group(function (){
            Route::get('/{subjectTeacherId}', 'FirstCAController@index')->name('index');
            Route::post('/store', 'FirstCAController@store')->name('store');
            
        });
        // routes first ca 
        Route::name('secondca.')
        ->prefix('/second-ca')
        ->group(function (){
            Route::get('/{subjectTeacherId}', 'SecondCAController@index')->name('index');
            Route::post('/store', 'SecondCAController@store')->name('store');
            
        });

        // routes assignmant
        Route::name('assignment.')
        ->prefix('/assignment')
        ->group(function (){
            Route::get('/{subjectTeacherId}', 'AssignmentController@index')->name('index');
            Route::post('/store', 'AssignmentController@store')->name('store');
            
        });
        // routes first ca 
        Route::name('exam.')
        ->prefix('/exam')
        ->group(function (){
            Route::get('/{subjectTeacherId}', 'ExamController@index')->name('index');
            Route::post('/store', 'ExamController@store')->name('store');
            Route::get('/{uploadId}/submit', 'ExamController@submit')->name('submit');
        });
    });
});