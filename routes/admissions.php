<?php
use App\Http\Controllers\Section\StudentController;

Route::middleware(['auth','verified', 'admission'])->name('admission.')->prefix('admission/')->group(function () {
    Route::name('student.')
    ->prefix('/student')
    ->namespace('Section')
    ->group(function (){
        Route::get('/', 'StudentController@index')->name('index');
        Route::post('/search', 'StudentController@search')->name('search');
        Route::get('/{classId}', 'StudentController@view')->name('view');
        Route::get('/{classId}/create', 'StudentController@create')->name('create');
        Route::get('/download', 'StudentController@download')->name('download');
        Route::get('/student/{studentId}/delete', 'StudentController@delete')->name('delete');
        Route::get('/student/{studentId}/edit', 'StudentController@edit')->name('edit');
        Route::post('/student/{studentId}/update', 'StudentController@update')->name('update');
        Route::post('/{classId}/register', 'StudentController@register')->name('register');

    });
});