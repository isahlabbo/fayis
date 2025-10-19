<?php
Route::middleware(['auth','verified','head'])
->namespace('examination')
->name('exam.')->prefix('exam/')->group(function () {

});