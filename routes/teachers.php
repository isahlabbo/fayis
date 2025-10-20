<?php

Route::middleware(['auth','verified','teacher'])
->namespace('Teacher')
->name('teacher.')->prefix('teacher/')->group(function () {
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
    });
});