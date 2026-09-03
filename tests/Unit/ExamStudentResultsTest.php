<?php

namespace Tests\Unit;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExamStudentResultsTest extends TestCase
{
    public function test_exam_student_result_routes_and_pdf_engine_are_available()
    {
        $this->assertTrue(Route::has('exam.student-results.index'));
        $this->assertTrue(Route::has('exam.student-results.download'));
        $this->assertTrue(Route::has('exam.result-access-codes.index'));
        $this->assertTrue(Route::has('exam.result-access-codes.generate'));
        $this->assertTrue(class_exists(Dompdf::class));

        $middleware = Route::getRoutes()->getByName('exam.student-results.index')->gatherMiddleware();
        $this->assertContains('permission:view-student-results', $middleware);

        $accessCodeMiddleware = Route::getRoutes()->getByName('exam.result-access-codes.index')->gatherMiddleware();
        $this->assertContains('permission:view-result-access-codes', $accessCodeMiddleware);

        $this->assertSame(
            'http://localhost/exam/student-results',
            route('exam.student-results.index')
        );
    }
}
