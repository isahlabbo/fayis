<?php
use App\Models\Section;
// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Dashboard/comment
Breadcrumbs::for('dashboard.comment', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Comment', route('dashboard.comment.index'));
});

// Dashboard/comment/view
Breadcrumbs::for('dashboard.comment.view', function ($trail) {
    $trail->parent('dashboard.comment');
    $trail->push('Available Comment', route('dashboard.comment.view'));
});


// Dashboard/Academic Session
Breadcrumbs::for('dashboard.session', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Academic Session', route('dashboard.session.index'));
});

// Dashboard/Academic Session/Configure
Breadcrumbs::for('dashboard.session.configure', function ($trail,$academicSession) {
    $trail->parent('dashboard.session',$academicSession->id);
    $trail->push($academicSession->name.' Configuration', route('dashboard.session.configure',$academicSession->id));
});

// Dashboard/Check  Result
Breadcrumbs::for('dashboard.result', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Check Result', route('dashboard.section.class.subject.result.index'));
});

// Dashboard/Check  Result
Breadcrumbs::for('dashboard.result.student', function ($trail, $student) {
    $trail->parent('dashboard.result');
    $trail->push($student->admission_no.' Result', route('dashboard.section.class.student.result.view',[$student->id]));
});

// Dashboard/Check  Result/subject class
Breadcrumbs::for('dashboard.result.summary', function ($trail, $session, $term, $sectionClassSubject) {
    $trail->parent('dashboard.result');
    $trail->push($sectionClassSubject->sectionClass->name.' '.$sectionClassSubject->name, route('dashboard.section.class.subject.result.summary',[$session->id, $term->id, $sectionClassSubject->id]));
});

// Dashboard/Check  Result/subject class/detail
Breadcrumbs::for('dashboard.result.summary.detail', function ($trail,  $session, $term, $sectionClassSubject) {
    $trail->parent('dashboard.result.summary', $session, $term, $sectionClassSubject);
    $trail->push('Detail', route('dashboard.section.class.subject.result.summary.detail',[$sectionClassSubject->id]));
});

// Dashboard/Check  Result/subject class/detail/edit
Breadcrumbs::for('dashboard.result.summary.detail.edit', function ($trail,  $session, $term, $studentResult) {
    $trail->parent('dashboard.result.summary.detail', $session, $term, $studentResult->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject);
    $trail->push('Edit', route('dashboard.section.class.subject.result.summary.detail',[$studentResult->id]));
});


// Dashboard/Section
Breadcrumbs::for('dashboard.section', function ($trail, $section) {
    $trail->parent('dashboard');
    $trail->push($section->name, route('dashboard.section.index',[$section->id]));
});

// dashboard/section/class
Breadcrumbs::for('dashboard.section.class', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section',$sectionClass->section);
    $trail->push($sectionClass->name, route('dashboard.section.class.student',[$sectionClass->id]));
});

// dashboard/section/class/exam
Breadcrumbs::for('dashboard.section.class.exam', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section.class',$sectionClass);
    $trail->push('Exam Setting', route('dashboard.section.class.exam.index',[$sectionClass->id]));
});

// dashboard/section/class/exam/subject
Breadcrumbs::for('dashboard.section.class.exam.subject', function ($trail, $exam) {
    $trail->parent('dashboard.section.class.exam',$exam->sectionClass);
    $trail->push('Subjects', route('dashboard.section.class.exam.subject',[$exam->sectionClass->id,$exam->id]));
});

// dashboard/section/class/exam/subject/question
Breadcrumbs::for('dashboard.section.class.exam.subject.question', function ($trail, $subject) {
    $trail->parent('dashboard.section.class.exam.subject',$subject->currentExam());
    $trail->push($subject->subject->name.'-questions', 
    route('dashboard.section.class.exam.subject.question.index',[$subject->sectionClass->id,$subject->id]));
});
// dashboard/section/class/exam/subject/question/view
Breadcrumbs::for('dashboard.section.class.exam.subject.question.view', function ($trail, $question) {
    $trail->parent('dashboard.section.class.exam.subject.question',$question->examSubjectQuestionSection->sectionClassSubject);
    $trail->push('view', 
    route('dashboard.section.class.exam.subject.question.index',[$question->examSubjectQuestionSection->sectionClassSubject->sectionClass->id,$question->id]));
});

// dashboard/section/class/exam/subject/question-section
Breadcrumbs::for('dashboard.section.class.exam.subject.question.section', function ($trail, $exam, $subject) {
    $trail->parent('dashboard.section.class.exam.subject',$exam);
    $trail->push($subject->subject->name.'-question-section', 
    route('dashboard.section.class.exam.subject.question.section.index',[$exam->sectionClass->id,$subject->id]));
});

// dashboard/section/class/edit student info
Breadcrumbs::for('dashboard.section.class.student.edit', function ($trail, $student) {
    $trail->parent('dashboard.section.class',$student->activeSectionClass());
    $trail->push('Edit Student Info', route('dashboard.section.class.student.edit',[$student->id]));
});

// dashboard/section/class/fee
Breadcrumbs::for('dashboard.section.class.fee', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section.class',$sectionClass);
    $trail->push('Class Payment Management', route('dashboard.payment.class.fee.index',[$sectionClass->id]));
});

// dashboard/section/class/result
Breadcrumbs::for('dashboard.section.class.result', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section.class',$sectionClass);
    $trail->push('Result Management', route('dashboard.section.class.subject.result',[$sectionClass->id]));
});


// dashboard/section/class/subject
Breadcrumbs::for('dashboard.section.class.subject', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section.class',$sectionClass);
    $trail->push('Subjects', route('dashboard.section.class.subject.index',[$sectionClass->id]));
});

// dashboard/section/class/subject/create-allocation
Breadcrumbs::for('dashboard.section.class.subject.allocation.create', function ($trail, $sectionClassSubject) {
    $trail->parent('dashboard.section.class',$sectionClassSubject->sectionClass);
    $trail->push('create-allocation', route('dashboard.section.class.subject.allocation.create',[$sectionClassSubject->id]));
});

// dashboard/section/class/subject/allocation
Breadcrumbs::for('dashboard.section.class.subject.allocation.reCreate', function ($trail, $sectionClassSubject) {
    $trail->parent('dashboard.section.class',$sectionClassSubject->sectionClass);
    $trail->push('re-allocation', route('dashboard.section.class.subject.allocation.reCreate',[$sectionClassSubject->id]));
});

Breadcrumbs::for('dashboard.section.class-teacher.create', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section',$sectionClass->section);
    $trail->push('create-class-teacher', route('dashboard.section.class-teacher.create',[$sectionClass->id]));
});

Breadcrumbs::for('dashboard.section.class-teacher.reCreate', function ($trail, $sectionClass) {
    $trail->parent('dashboard.section',$sectionClass->section);
    $trail->push('change-class-teacher', route('dashboard.section.class-teacher.reCreate',[$sectionClass->id]));
});

// Daashboard/Teacher
Breadcrumbs::for('dashboard.teacher', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Teachers', route('dashboard.teacher.index'));
});

// Daashboard/Teacher/Subject
Breadcrumbs::for('dashboard.teacher.subject', function ($trail,$teacher) {
    $trail->parent('dashboard.teacher');
    $trail->push('Subjects', route('dashboard.section.class.subject.allocation.index',[$teacher->id]));
});

// Daashboard/Teacher/Subject/upload-result
Breadcrumbs::for('dashboard.teacher.subject.upload', function ($trail,$teacher) {
    $trail->parent('dashboard.teacher.subject',$teacher);
    $trail->push('Upload Result', route('dashboard.teacher.upload.scoresheet',[$teacher->id]));
});


Breadcrumbs::for('dashboard.teacher.create', function ($trail) {
    $trail->parent('dashboard.teacher');
    $trail->push('Create', route('dashboard.teacher.create'));
});

Breadcrumbs::for('dashboard.student', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Students', route('dashboard.student.index'));
});

Breadcrumbs::for('dashboard.student.create', function ($trail) {
    $trail->parent('dashboard.student');
    $trail->push('Create', route('dashboard.student.create'));
});