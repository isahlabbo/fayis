<?php

Route::middleware(['auth','verified'])->name('configuration.')->group(function () {
    Route::name('reportcard.')
    ->prefix('/report-card')
    ->group(function (){
        Route::name('psychomotor.')
        ->prefix('/psycomotor')
        ->group(function (){
            Route::post('/register', 'PsychomotorController@register')->name('register');
            Route::post('/{psychomotorId}/update', 'PsychomotorController@update')->name('update');
            Route::get('/{psychomotorId}/delete', 'PsychomotorController@delete')->name('delete');
        });

        Route::name('affectivetrait.')
        ->prefix('/affective-trait')
        ->group(function (){
            Route::post('/register', 'AffectiveTraitController@register')->name('register');
            Route::post('/{affectiveTraitId}/update', 'AffectiveTraitController@update')->name('update');
            Route::get('/{affectiveTraitId}/delete', 'AffectiveTraitController@delete')->name('delete');
        });
        
        Route::name('remark.')
        ->prefix('/remark')
        ->group(function (){
            Route::post('/register', 'RemarkController@register')->name('register');
            Route::post('/{remarkId}/update', 'RemarkController@update')->name('update');
            Route::get('/{remarkId}/delete', 'RemarkController@delete')->name('delete');
        });

        Route::name('grade.')
        ->prefix('/grade')
        ->group(function (){
            Route::post('/register', 'GradeController@register')->name('register');
            Route::post('/{gradeId}/update', 'GradeController@update')->name('update');
            Route::get('/{gradeId}/delete', 'GradeController@delete')->name('delete');
        });
        Route::name('letter.')
        ->prefix('/letter')
        ->group(function (){
            Route::post('/update', 'ReportCardConfigurationController@updateLetter')->name('update');
        });
        Route::get('/', 'ReportCardConfigurationController@index')->name('index');
    });
});