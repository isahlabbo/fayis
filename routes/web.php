<?php

use Illuminate\Support\Facades\Route;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Invoice;
use App\Models\Student;
use Modules\Admin\Entities\Admin;
use App\Models\AcademicSession;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Notifications\SchoolFeesPaymentCollectedSMS;
use App\Charts\TermVsClassAverageChart;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Hash;
Route::prefix('ajax')
   ->group(function() {
    Route::get('/section/{sectionId}/get-classes', 'AjaxController@getSectionClasses');
    Route::get('/class/{classId}/get-students', 'AjaxController@getClassStudents');
    Route::get('/section/class/{sectionClassId}/get-subjects', 'AjaxController@getClassSubjects');
    Route::get('/address/state/{stateId}/get-lgas', 'AjaxController@getLgas');

});
Route::prefix('result')->name('result.')
   ->group(function() {
    Route::get('/check', 'ResultSearchController@check')->name('check');
    Route::post('/search', 'ResultSearchController@search')->name('search');
    Route::get('/{stutenTermId}/guardian', 'ResultSearchController@guardian')->name('guardian');
    Route::get('/{stutenTermId}/view', 'ResultSearchController@view')->name('view');
    Route::post('/guardian/{guardianId}/result/{stutenTermId}', 'ResultSearchController@updateGuardian')->name('guardian.update');
});

Route::prefix('payment')->name('payment.')
   ->group(function() {
    Route::get('/', 'PaymentController@index')->name('index');
    Route::get('/class/{classId}/students/{type}', 'PaymentController@students')->name('students');
    Route::post('/verify', 'PaymentController@verifyClass')->name('verify');
    Route::get('/{sectionclassStudentId}/invoices', 'PaymentController@invoice')->name('invoice');
    
    Route::get('/{classId}/generate-invoice', 'PaymentController@generateInvoice')->name('generate.invoice');
    Route::post('/{classId}/generate-invoice-only', 'PaymentController@generateInvoiceOnly')->name('generate.invoice.only');
    
    Route::get('/{invoiceId}/cancelled', 'PaymentController@cancelled')->name('cancelled');
    Route::post('/search', 'PaymentController@search')->name('search');
    Route::post('/{sactionClassStudentId}/update-and-generate-invoice', 'PaymentController@updateAndGenerateInvoice')->name('update.generate.invoice');

    Route::get('/invoice/{invoiceId}/receipt', 'PaymentController@receipt')->name('receipt');        
    Route::get('/{invoiceId}/callback', 'PaymentController@callback')->name('callback');        
    Route::post('/pay', 'PaymentController@pay')->name('pay');
});


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/download/data', function () {
    $fileName = 'data.xlsx';
    return Excel::download(new DataExport, $fileName); // stores in storage/app/public/


})->name('download.data');

Route::get('/access/restricted', function () {
    return view('access.restrict');
})->name('access.restricted');


Route::get('/sms', function () {
     $response = Admin::find(1)->notify(new SchoolFeesPaymentCollectedSMS(Invoice::find(1)));
     
});


Route::middleware(['auth:sanctum', 'verified','password'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])
->group(function (){
    Route::name('password.')
    ->prefix('/password')
    ->group(function (){
        Route::get('/update', 'PasswordController@showUpdateForm')->name('update.form');
        Route::post('/update', 'PasswordController@updatePassword')->name('update');
    });
});

Route::middleware(['auth:sanctum', 'verified', 'password'])
->group(function (){
    // configuration
    Route::name('profile.')
    ->prefix('/profile')
    ->group(function (){
        Route::get('/', 'ProfileController@show')->name('show');
        Route::get('/{userId}/card', 'ProfileController@card')->name('card');
        Route::put('/{userId}/update', 'ProfileController@update')->name('update');
        Route::post('/{userId}/card-request', 'ProfileController@cardRequest')->name('cardRequest');
    });

    Route::name('configuration.')
    ->prefix('/configuration')
    ->group(function (){
        //configuration > permissions
        Route::name('role.')
        ->prefix('/role')
        ->group(function (){
            Route::get('/', 'RoleController@index')->name('index');
        });

        Route::name('permission.')
        ->prefix('/permission')
        ->group(function (){
            Route::get('/', 'PermissionController@index')->name('index');
        });
    });

});



Route::name('dashboard.')
->prefix('dashboard')
->middleware(['auth:sanctum', 'verified'])
->group(function (){
    Route::name('epayment.')
    
    ->prefix('/epayment')
    ->group(function (){
        Route::get('/', 'EpaymentController@index')->name('index')->middleware('authorized');
        Route::get('/download', 'EpaymentController@download')->name('download');
        Route::get('/{classId}/class', 'EpaymentController@class')->name('class')->middleware('authorized');
        Route::get('/{sectionClassStudentId}/invoice/pay', 'EpaymentController@invoice')->name('invoice')->middleware('authorized');
        Route::get('/{invoiceId}/callback', 'EpaymentController@callback')->name('callback')->middleware('authorized');
        Route::post('/{invoiceId}/clear-now', 'EpaymentController@clear')->name('clear')->middleware('authorized');
        Route::get('/{invoiceId}/receipt', 'EpaymentController@receipt')->name('receipt')->middleware('authorized');
        Route::post('/pay', 'EpaymentController@pay')->name('pay')->middleware('authorized');
        Route::post('/{classId}/add-student', 'EpaymentController@addStudent')->name('student.add')->middleware('authorized');
        Route::post('/search', 'EpaymentController@search')->name('search')->middleware('authorized');
        Route::post('/{sectionClassStudentId}/update', 'EpaymentController@update')->name('update')->middleware('authorized');       
    });

    
    Route::name('application.')
    ->prefix('/application')
    ->group(function (){
        Route::get('/{sessionId}', 'ApplicationController@index')->name('index');
        Route::get('/{applicationId}/view', 'ApplicationController@view')->name('view');
        Route::get('/{tokenId}/create', 'ApplicationController@create')->name('create');
        Route::post('/{tokenId}/register', 'ApplicationController@register')->name('register');
    });
    Route::name('staff.')
    ->prefix('/staff')
    ->group(function (){
        Route::post('/verify', 'StaffController@verify')->name('verify');
        
    });
    
    Route::name('guardian.')
    ->prefix('/guardian')
    ->group(function (){
        Route::get('/{userId}/update', 'GuardianController@update')->name('update');
        Route::post('/verify', 'GuardianController@verifyChild')->name('verify');
        Route::get('/{studentId}/verified', 'GuardianController@verified')->name('child.verified');
        Route::get('/{guardianId}/send-message', 'GuardianController@sendMessage');
        Route::post('/{studentId}/link', 'GuardianController@linkChild')->name('child.link');
    });

    Route::name('payment.')
    ->prefix('/payment')
    ->group(function (){
        Route::post('/initiate', 'PaymentController@initiate')->name('initiate');
        Route::get('/callback', 'PaymentController@callback')->name('callback');
    });

    Route::name('fee.')
    ->prefix('/fee')
    ->group(function (){
        Route::get('/review', 'FeeController@review')->name('review');
    });

    
    Route::namespace('Section')
    ->name('comment.')
    ->prefix('/comment')
    ->group(function (){
        Route::get('/', 'CommentController@index')->name('index');
        Route::get('/view', 'CommentController@view')->name('view');
        Route::post('/add', 'CommentController@addComment')->name('add');
        Route::post('/teacher-comment/{teacherCommentId}/update', 'CommentController@updateTeacherComment')->name('teacher.update');
        Route::post('/head-teacher-comment/{headteacherCommentId}/update', 'CommentController@updateHeadTeacherComment')->name('headteacher.update');
        Route::get('/teacher-comment/{teacherCommentId}/delete', 'CommentController@deleteTeacherComment')->name('teacher.delete');
        Route::get('/head-teacher-comment/{headteacherCommentId}/delete', 'CommentController@deleteHeadTeacherComment')->name('headteacher.delete');
    });
    // school section routes
    








        // section class teachers routes
        

        
        Route::name('class.')
        ->prefix('/class')
        ->group(function (){
            Route::post('/{sectionId}/register', 'SectionClassController@register')->name('register');
            Route::post('/{sectionClassId}/update', 'SectionClassController@updateClass')->name('update');
            Route::get('/{sectionClassId}/delete', 'SectionClassController@deleteClass')->name('delete');
            Route::get('/{sectionClassId}/promotion', 'SectionClassController@promotion')->name('promotion');
            Route::name('result.')
            ->prefix('/{sectionClassId}/result')
            ->group(function (){
                Route::get('session/{sessionId}/term/{termId}/summary', 'ClassResultController@summary')->name('summary');
                Route::get('/report', 'ClassResultController@report')->name('report');
                Route::get('/accessment/download', 'ClassResultController@downloadAccessment')->name('accessment.download');
                Route::get('/accessment/view', 'ClassResultController@viewAccessment')->name('accessment.view');
                Route::get('/accessment/{studentTermId}/edit', 'ClassResultController@editAccessment')->name('accessment.edit');
                Route::post('/accessment/{studentTermId}/update', 'ClassResultController@updateAccessment')->name('accessment.update');
                Route::post('/accessment/upload', 'ClassResultController@uploadAccessment')->name('accessment.upload');
            });
            Route::name('fee.')
                ->prefix('/{classId}/fee')
                ->group(function (){
                    Route::get('/', 'ClassFeeController@index')->name('index');
                    Route::post('/register', 'ClassFeeController@register')->name('register');
                    Route::post('/{feeId}/update', 'ClassFeeController@update')->name('update');
                    Route::get('/{feeId}/delete', 'ClassFeeController@delete')->name('delete');
                });
            // exam routes
            Route::name('exam.')
            ->prefix('{classId}/exam')
            ->group(function (){
                Route::get('/', 'ExamController@index')->name('index');

                Route::post('/subject/{questId}/new-item', 'ExamController@newItem')->name('question.newItem');
                Route::post('/subject/{questId}/new-option', 'ExamController@newOption')->name('question.newOption');
                Route::get('/question/item/{itemId}/delete-item', 'ExamController@deleteItem')->name('question.delete.item');
                Route::get('/question/option/{optionId}/delete-option', 'ExamController@deleteOption')->name('question.delete.option');
                Route::get('/{examId}/subjects', 'ExamController@examSubject')->name('subject');
                Route::get('/subject/{subjectId}/question-papers', 'ExamController@subjectQuestionPaper')->name('subject.question.paper');
                Route::post('/register', 'ExamController@register')->name('register');
                
                
                Route::name('subject.')
                ->group(function (){
                    Route::name('question.')
                    ->group(function (){
                        Route::post('/subject/question/register', 'QuestionController@register')->name('register');
                        Route::post('/subject/question/move', 'QuestionController@move')->name('move');
                        Route::post('/subject/question/copy', 'QuestionController@copy')->name('copy');
                        Route::get('/subject/{subjectId}/questions', 'QuestionController@index')->name('index');
                        Route::get('/subject/questions/{questionId}/view', 'QuestionController@view')->name('view');
                        Route::get('/subject/questions/{questionId}/delete', 'QuestionController@delete')->name('delete');
                        Route::post('/subject/questions/{questionId}/update', 'QuestionController@update')->name('update');
                    //    question section route
                        Route::name('section.')
                        ->prefix('/subject/{subjectId}/section')
                        ->group(function (){
                            Route::get('/', 'QuestionSectionController@index')->name('index');
                            Route::post('/register', 'QuestionSectionController@register')->name('register');
                        });
                    });
                });
            });
            
            Route::get('/{sectionClassId}/student-admission-no', 'SectionClassController@reGenerateAdmissionNo')->name('admission.number.regenerate');
            Route::get('/{sectionClassId}', 'SectionClassController@index')->name('index');
            Route::get('/{sectionClassId}/students', 'SectionClassController@student')->name('student');
            Route::get('/student/{studentId}/letter', 'SectionClassController@letter')->name('student.letter');
            Route::get('/student/{studentId}/delete', 'SectionClassController@delete')->name('student.delete');
            Route::get('/student/{studentId}/edit', 'SectionClassController@edit')->name('student.edit');
            Route::post('/student/{studentId}/update', 'SectionClassController@update')->name('student.update');
            
            

            // subject results routes
            Route::name('student.result.')
                ->prefix('/student/result')
                ->group(function (){
                    Route::get('/{studentId}/view', 'StudentResultController@view')->name('view');
            });
            // subject results routes
            Route::name('subject.')
            ->prefix('/subject')
            ->group(function (){
                Route::name('result.')
                    ->prefix('/result')
                    ->group(function (){
                        Route::get('/', 'ResultSearchController@index')->name('index');
                        Route::post('/check-result', 'ResultSearchController@checkResult')->name('check');
                        Route::get('{sectionClassSubjectId}/session/{sessionId}/term/{termId}/summary', 'ResultSearchController@viewResultSummary')->name('summary');
                        Route::get('/summary/{subjectTeacherUploadId}/detail', 'ResultSearchController@viewDetail')->name('summary.detail');
                        Route::get('/summary/{subjectTeacherUploadId}/delete', 'ResultSearchController@deleteUpload')->name('summary.delete');
                        Route::get('/summary/detail/{studentResultId}/edit', 'ResultSearchController@editResult')->name('summary.detail.edit');
                        Route::post('/summary/detail/{studentResultId}/update', 'ResultSearchController@updateResult')->name('summary.detail.update');
                });
            });
            
        }); 
    });

    // teachers routes
   
    // student routes
    

require __DIR__.'/configurations.php';
require __DIR__.'/sections.php';
require __DIR__.'/administrations.php';
require __DIR__.'/admissions.php';
require __DIR__.'/finances.php';
require __DIR__.'/teachers.php';
require __DIR__.'/exams.php';
require __DIR__.'/patron.php';
require __DIR__.'/admin.php';
