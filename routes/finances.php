<?php

Route::middleware(['auth', 'verified', 'password', 'finance'])->prefix('/finance')->namespace('Finance')->name('finance.')->group(function () {
    Route::view('/fee-settings', 'finance.livewire-page', ['title'=>'Fees Setting','component'=>'finance.fee-settings'])->middleware('permission:manage-fees')->name('fee-settings');
    Route::view('/activity-report', 'finance.livewire-page', ['title'=>'Finance Reports','component'=>'finance.reports'])->middleware('permission:manage-payments')->name('activity-report');
    
    Route::name('fees.')
    ->prefix('/fee')
    ->middleware('permission:manage-fees')
    ->group(function (){
        Route::get('/{sectionId}/classes', 'FeeController@classes')->name('classes');
        Route::get('/{sectionClassId}', 'FeeController@index')->name('index');
        Route::post('/{sectionClassFeeId}/add', 'FeeController@addItem')->name('addItem');
        Route::post('/{sectionClassFeeItemId}/update', 'FeeController@updateItem')->name('updateItem');
        Route::get('/{sectionClassFeeItemId}/delete', 'FeeController@deleteItem')->name('deleteItem');
    });  

    Route::name('advance-payments.')->prefix('/advance-payment')->middleware('permission:manage-payments')->group(function () {
        Route::view('/monitor', 'finance.payments.advance_monitor')->name('monitor');
        Route::get('/collect/{feeId}', function ($feeId) { return view('finance.payments.advance', compact('feeId')); })->name('collect');
        Route::get('/{advancePayment}/receipt', function (\App\Models\AdvancePayment $advancePayment) {
            $advancePayment->load(['student','fee','academicSession','sectionClass','term']);
            $lines = \App\Models\AdvancePayment::with(['fee','academicSession','sectionClass','term'])
                ->where('receipt_group',$advancePayment->receipt_group)->get();
            return view('finance.payments.advance_receipt', ['advance'=>$advancePayment,'lines'=>$lines]);
        })->name('receipt');
    });
    
    Route::name('payments.')
    ->prefix('/payment')
    ->middleware('permission:manage-payments')
    ->group(function (){
        Route::get('/collect/{feeId}', function ($feeId) { return view('finance.payments.collect', compact('feeId')); })->middleware('permission:manage-payments')->name('collect');
        Route::get('/report', 'PaymentReportController@index')->name('report');
        Route::get('/report/unpaid', 'PaymentReportController@unpaid')->name('unpaid');
        Route::get('/report/unpaid/pdf', 'PaymentReportController@unpaidPdf')->name('unpaid.pdf');
        Route::get('/report/unpaid/csv', 'PaymentReportController@unpaidCsv')->name('unpaid.csv');
        Route::get('/report/pdf', 'PaymentReportController@pdf')->name('report.pdf');
        Route::get('/report/csv', 'PaymentReportController@csv')->name('report.csv');
        Route::get('/{sectionId}/classes/{type?}', 'PaymentController@classes')->name('classes');
        Route::get('/{sectionClassId}', 'PaymentController@index')->name('index');
        Route::post('/{paymentId}/add', 'PaymentController@add')->name('add');
        Route::put('/{paymentId}/update', 'PaymentController@update')->name('update');
        Route::get('/{paymentId}/delete', 'PaymentController@delete')->name('delete');
        Route::get('/{paymentId}/receipt', 'PaymentController@receipt')->name('receipt');
        Route::get('/{paymentId}/receipt/pdf', 'PaymentController@receiptPdf')->name('receipt.pdf');
    });  

    Route::name('inventory.')
    ->prefix('/inventory')
    ->group(function (){
        Route::middleware('permission:manage-inventory')->group(function () {
            Route::view('/view', 'finance.inventory.view')->name('view');
            Route::view('/stock', 'finance.inventory.stock')->name('stock');
            Route::view('/categories', 'finance.inventory.categories')->name('categories');
            Route::view('/categories/crud', 'finance.inventory.categories_crud')->name('categories.crud');
            Route::view('/reconcile', 'finance.inventory.reconcile')->name('reconcile');
            Route::view('/usage', 'finance.inventory.usage')->name('usage');
            Route::get('/usage/{usageId}/receipt', 'InventoryUsageController@receipt')->name('usage.receipt');
            Route::get('/view/pdf', 'InventoryExportController@viewPdf')->name('view.pdf');
            Route::get('/stock/pdf', 'InventoryExportController@stockPdf')->name('stock.pdf');
            Route::get('/categories/pdf', 'InventoryExportController@categoriesPdf')->name('categories.pdf');
            Route::get('/reconcile/pdf', 'InventoryExportController@reconcilePdf')->name('reconcile.pdf');
        });
        Route::view('/sales', 'finance.inventory.sales')->middleware('permission:manage-sales,manage-inventory')->name('sales');
        Route::view('/rents', 'finance.inventory.rents')->middleware('permission:manage-rents,manage-inventory')->name('rents');
        Route::get('/sales/{saleId}/receipt', 'InventorySaleController@receipt')->middleware('permission:manage-sales,manage-inventory')->name('sales.receipt');
        Route::get('/rents/{rentId}/receipt', 'InventoryRentController@receipt')->middleware('permission:manage-rents,manage-inventory')->name('rents.receipt');

        Route::get('/sales/pdf', 'InventoryExportController@salesPdf')->middleware('permission:manage-sales,manage-inventory')->name('sales.pdf');
    });
 
});
