<?php
use App\Http\Controllers\Section\StudentController;

Route::middleware(['auth','verified', 'admission', 'password'])->name('admission.')->prefix('admission/')->group(function () {
    Route::view('/applications', 'admission.livewire-page', ['title'=>'Applications','component'=>'admission.applications'])->middleware('permission:manage-admissions')->name('applications');
    Route::view('/approvals', 'admission.livewire-page', ['title'=>'Admissions','component'=>'admission.admissions'])->middleware('permission:manage-admissions')->name('approvals');
    Route::view('/students', 'admission.livewire-page', ['title'=>'Students','component'=>'admission.students'])->middleware('permission:manage-admissions')->name('students');
    Route::view('/promotions', 'admission.livewire-page', ['title'=>'Student Promotions','component'=>'admission.promotions'])->name('promotions');
    Route::view('/guardians', 'admission.livewire-page', ['title'=>'Guardians','component'=>'admission.guardians'])->middleware('permission:manage-admissions')->name('guardians');
    Route::name('student.')
    ->prefix('/student')
    ->namespace('Section')
    ->group(function (){
        Route::get('/', 'StudentController@index')->name('index');
        Route::post('/search', 'StudentController@search')->name('search');
        Route::get('/{classId}', 'StudentController@view')->name('view');
        Route::get('/{classId}/create', 'StudentController@create')->name('create');
        Route::get('/download', 'StudentController@download')->name('download');
        Route::delete('/student/{studentId}/delete', 'StudentController@delete')->name('delete');
        Route::get('/student/{studentId}/edit', 'StudentController@edit')->name('edit');
        Route::post('/student/{studentId}/update', 'StudentController@update')->name('update');
        Route::post('/{classId}/register', 'StudentController@register')->name('register');

    });
});
