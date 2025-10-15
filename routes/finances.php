<?php

Route::middleware(['auth','verified','finance'])->prefix('/finance')->namespace('Finance')->name('finance.')->group(function () {
    
    Route::name('fees.')
    ->prefix('/fee')
    ->group(function (){
        Route::get('/{sectionId}/classes', 'FeeController@classes')->name('classes');
        Route::get('/{sectionClassId}', 'FeeController@index')->name('index');
        Route::post('/{sectionClassFeeId}/add', 'FeeController@addItem')->name('addItem');
        Route::post('/{sectionClassFeeItemId}/update', 'FeeController@updateItem')->name('updateItem');
        Route::get('/{sectionClassFeeItemId}/delete', 'FeeController@deleteItem')->name('deleteItem');
    });  
    
    Route::name('payments.')
    ->prefix('/payment')
    ->group(function (){
        Route::get('/{sectionId}/classes', 'PaymentController@classes')->name('classes');
        Route::get('/{sectionClassId}', 'PaymentController@index')->name('index');
        Route::post('/{paymentId}/add', 'PaymentController@add')->name('add');
        Route::put('/{paymentId}/update', 'PaymentController@update')->name('update');
        Route::get('/{paymentId}/delete', 'PaymentController@delete')->name('delete');
        Route::get('/{paymentId}/receipt', 'PaymentController@receipt')->name('receipt');
    });  
 
});