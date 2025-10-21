<?php

Route::middleware(['auth','verified','teacher'])
->namespace('Teacher')
->name('teacher.')->prefix('teacher/')->group(function () {
    
    Route::name('class.')
    ->prefix('/class')
    ->group(function (){
        Route::get('{classId}/', 'ClassController@index')->name('index');
    //    assessment routes
        Route::name('assessment.')
        ->prefix('/assessment')
        ->group(function (){
            Route::get('{classTeacherId}/', 'AssessmentController@index')->name('index');
            Route::get('{studenTermId}/edit', 'AssessmentController@edit')->name('edit');
            Route::post('{studentClassId}', 'AssessmentController@update')->name('update');
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
        // routes first ca 
        Route::name('exam.')
        ->prefix('/exam')
        ->group(function (){
            Route::get('/{subjectTeacherId}', 'ExamController@index')->name('index');
            Route::post('/store', 'ExamController@store')->name('store');
            
        });
    });
});