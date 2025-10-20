<?php
Route::middleware(['auth','verified','head'])
->namespace('Examination')
->name('exam.')->prefix('exam/')->group(function () {

});