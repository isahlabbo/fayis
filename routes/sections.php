<?php
use App\Http\Controllers\School\StudentController;

Route::middleware(['auth','verified'])
->namespace('Section')
->name('section.')->prefix('section/')->group(function () {
    // section
    Route::get('/{sectionId}', 'SectionController@index')->name('index');
    Route::get('/{sectionId}/delete', 'SectionController@delete')->name('delete');
    Route::get('/{sectionId}/classes', 'SectionController@classes')->name('classes');
    Route::post('/register', 'SectionController@register')->name('register');
    Route::post('/{sectionId}/update', 'SectionController@update')->name('update');
    Route::get('/', 'SectionController@index')->name('index');
    // section/class
    Route::name('class.')
    ->prefix('/class')
    ->group(function (){
        Route::post('/{sectionId}/register', 'SectionClassController@register')->name('register');
        Route::post('/{sectionClassId}/update', 'SectionClassController@updateClass')->name('update');
        Route::get('/{sectionClassId}/delete', 'SectionClassController@deleteClass')->name('delete');
        Route::get('/{sectionClassId}/promotion', 'SectionClassController@promotion')->name('promotion');
        Route::get('/{sectionClassId}', 'SectionClassController@index')->name('index');
        Route::get('/{sectionClassId}/downlod', 'SectionClassController@download')->name('download');
        Route::post('/{sectionClassId}/upload', 'SectionClassController@upload')->name('upload');
        // 
        Route::name('student.')
        ->prefix('/student')
        ->group(function (){
            
            Route::get('/{academicSessionTermId}/resume', 'StudentController@resume')->name('resume');
            Route::get('/{academicSessionTermId}/confirm-resume', 'StudentController@confirmResume')->name('resume.confirm');
            Route::get('/{sectionClassId}', 'StudentController@index')->name('index');
            Route::post('/search', 'StudentController@search')->name('search');
            Route::get('/{studentId}/profile', 'StudentController@profile')->name('profile');
            Route::get('/create', 'StudentController@create')->name('create');
            Route::get('/student/{studentId}/delete', 'StudentController@delete')->name('delete');
            Route::get('/student/{studentId}/edit', 'StudentController@edit')->name('edit');
            Route::post('/student/{studentId}/update', 'StudentController@update')->name('update');
            Route::post('/register', 'StudentController@register')->name('register');
            
            Route::name('accessment.')
            ->prefix('/accessment')
            ->group(function (){
                Route::get('/{sectionClassId}', 'StudentAccessmentController@index')->name('index');
                Route::get('/{sectionClassStudentId}/create', 'StudentAccessmentController@create')->name('create');
                Route::post('/{sectionClassStudentTermId}/register', 'StudentAccessmentController@register')->name('register');
            });
        });
        // section/class/subjects
        Route::name('subject.')
        ->prefix('/subject')
        ->group(function (){
            Route::get('/{classId}', 'SubjectController@index')->name('index');
            Route::put('/{classId}/register', 'SubjectController@register')->name('register');
            Route::get('/{subjectId}/term/{termId}', 'SubjectController@termResult')->name('termResult');
            Route::post('/{subjectId}/term/{termId}/upload/{uploadId}/save', 'SubjectController@updateUpload')->name('update.upload');
            Route::get('/{subjectId}/term/{termId}/upload/{uploadId}/result', 'SubjectController@updateResult')->name('upload.result');
            Route::get('/result', 'SubjectController@result')->name('result');

            Route::name('allocation.')
                ->prefix('/subject')
                ->group(function (){
                    Route::get('/{sectionClassSubjectId}/edit', 'SubjectTeacherAllocationController@edit')->name('edit');
                    Route::put('/{sectionClassSubjectId}/update', 'SubjectTeacherAllocationController@update')->name('update');
            });
        });
        // section/class/fees
        Route::name('fee.')
        ->prefix('/{classId}/fee')
        ->group(function (){
            Route::get('/', 'ClassFeeController@index')->name('index');
            Route::post('/register', 'ClassFeeController@register')->name('register');
            Route::post('/{feeId}/update', 'ClassFeeController@update')->name('update');
            Route::get('/{feeId}/delete', 'ClassFeeController@delete')->name('delete');
        });

        Route::name('teacher.')
        ->prefix('{sectionClassId}/teacher')
        ->group(function (){
            Route::get('create/', 'ClassTeacherController@create')->name('create');
            Route::get('re-create/', 'ClassTeacherController@reCreate')->name('reCreate');
            Route::post('register/', 'ClassTeacherController@register')->name('register');
        });

        // section class teachers routes
        Route::name('promotion.')
        ->prefix('/promotion')
        ->group(function (){
            Route::get('/', 'promotionController@index')->name('index');
            Route::get('/class/{sectionClassId}', 'promotionController@promoteAllStudent')->name('class');
        });

        Route::name('trobleshoot.')
        ->group(function (){
            Route::get('/{sectionId}/trobleshoot', 'TrobleshootController@index')->name('index');
            Route::get('/class/{sectionClassId}/trobleshoot', 'TrobleshootController@classReport')->name('class');
            Route::get('/class/{sectionClassId}/issue/{status}/fixed', 'TrobleshootController@fixedIssue')->name('issue.fixed');
        });

        Route::name('result.')
        ->group(function (){
            Route::get('/{sectionId}/result', 'SectionResultController@index')->name('index');
            Route::get('/class/{sectionClassId}/awaiting-result', 'SectionResultController@classAwaitingResult')->name('awaiting');
            Route::get('/class/{sectionClassId}/publish', 'SectionResultController@publishResult')->name('publish');
        });

    });
});