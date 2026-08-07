<?php

Route::middleware(['auth','verified', 'password'])->prefix('/finance')->namespace('Finance')->name('finance.')->group(function () {
    
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
        Route::get('/report', 'PaymentReportController@index')->name('report');
        Route::get('/report/pdf', 'PaymentReportController@pdf')->name('report.pdf');
        Route::get('/report/csv', 'PaymentReportController@csv')->name('report.csv');
        Route::get('/{sectionId}/classes/{type?}', 'PaymentController@classes')->name('classes');
        Route::get('/{sectionClassId}', 'PaymentController@index')->name('index');
        Route::post('/{paymentId}/add', 'PaymentController@add')->name('add');
        Route::put('/{paymentId}/update', 'PaymentController@update')->name('update');
        Route::get('/{paymentId}/delete', 'PaymentController@delete')->name('delete');
        Route::get('/{paymentId}/receipt', 'PaymentController@receipt')->name('receipt');
    });  

    Route::name('inventory.')
    ->prefix('/inventory')
    ->group(function (){
        Route::view('/view', 'finance.inventory.view')->name('view');
        Route::view('/stock', 'finance.inventory.stock')->name('stock');
        Route::view('/categories', 'finance.inventory.categories')->name('categories');
        Route::view('/categories/crud', 'finance.inventory.categories_crud')->name('categories.crud');
        Route::view('/reconcile', 'finance.inventory.reconcile')->name('reconcile');
        Route::view('/usage', 'finance.inventory.usage')->name('usage');
        Route::view('/sales', 'finance.inventory.sales')->name('sales');
        Route::view('/rents', 'finance.inventory.rents')->name('rents');
        Route::get('/sales/{saleId}/receipt', 'InventorySaleController@receipt')->name('sales.receipt');
        Route::get('/rents/{rentId}/receipt', 'InventoryRentController@receipt')->name('rents.receipt');
        Route::get('/usage/{usageId}/receipt', 'InventoryUsageController@receipt')->name('usage.receipt');

        Route::get('/view/pdf', 'InventoryExportController@viewPdf')->name('view.pdf');
        Route::get('/stock/pdf', 'InventoryExportController@stockPdf')->name('stock.pdf');
        Route::get('/categories/pdf', 'InventoryExportController@categoriesPdf')->name('categories.pdf');
        Route::get('/sales/pdf', 'InventoryExportController@salesPdf')->name('sales.pdf');
        Route::get('/reconcile/pdf', 'InventoryExportController@reconcilePdf')->name('reconcile.pdf');
    });
 
});