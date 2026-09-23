# FAYIS Database and Eloquent Model Reference

## 1. Purpose and scope

This document describes the FAYIS data model model-by-model. It was prepared from:

- the 96 PHP classes under `app/Models`;
- the 92 migrations under `database/migrations`;
- the live MySQL `fayis` schema in `information_schema`;
- model relationships and business methods used by the application.

The live schema is the authority for columns that currently exist. A relationship described as **logical** is declared or assumed by application code but is not enforced by a live MySQL foreign-key constraint.

All ordinary Eloquent tables use `id`, `created_at`, and `updated_at` unless stated otherwise. Most application models inherit `BaseModel`, whose `$guarded = []` means all columns are mass assignable. Controllers and Livewire components must therefore validate input carefully.

## 2. High-level domain map

```text
User ── Teacher ── Class/subject allocations ── Termly upload ── StudentResult
 │                    │                                      │
 ├── roles/permissions│                                      │
 └── card requests    │                                      ▼
                  Section ── SectionClass ── SectionClassStudent
                                           │                 │
AcademicSession ── AcademicSessionTerm ─────┘                 ▼
                                                     SectionClassStudentTerm
                                                       │       │       │
                                                       │       │       └── access code
                                                       │       ├── assessment/traits
                                                       │       └── published summary
                                                       └── student results

Guardian ── Student ── enrolments ── payments / sales / promotion history

InventoryCategory ── InventoryItem ── Stock batches / transactions
                                      ├── sales and sale lines
                                      ├── usages
                                      └── rents
```

The central academic identity chain is:

```text
Student
  -> SectionClassStudent (the student's class enrolment for one session)
    -> SectionClassStudentTerm (one term in that enrolment)
      -> AcademicSessionTerm
        -> AcademicSession
        -> Term
```

There is no direct `term_id` on `section_class_student_terms`. The correct access is always:

```php
$studentTerm->academicSessionTerm->term;
```

## 3. Important schema conventions and caveats

1. **Session versus term:** `academic_sessions` is the school year, `terms` is the reusable First/Second/Third Term lookup, and `academic_session_terms` joins the two and adds dates/status.
2. **Student enrolment:** `students.academic_session_id` represents the admission/intake session. The authoritative class history is `section_class_students`, which has its own `academic_session_id`.
3. **Results:** `student_results` belongs to a `section_class_student_term` and a `subject_teacher_termly_upload`. The upload redundantly stores `academic_session_id` and `term_id`; both must match the selected `AcademicSessionTerm` when auditing legacy data.
4. **Legacy foreign keys:** most tables created before 2026 have foreign-key-shaped columns but few actual MySQL constraints. Deleting parent rows can therefore leave orphans.
5. **Money and dates:** several older financial amounts and dates are `varchar(255)`, not decimal/date values. Newer inventory and advance-payment amounts use decimals.
6. **Status fields:** values are inconsistent in type and casing (`Active`, `Not Active`, `pending`, booleans, numeric upload states). Treat status values as domain-specific.
7. **Spelling:** database/model names retain historical spellings such as `Accessment`, `Profineciencies`, `diviated`, and `getway` for compatibility.
8. **Absent tables:** a number of legacy models have no corresponding table in the current live schema. They are documented in section 13 and should not be treated as operational without a migration/import.

## 4. Shared infrastructure models

### `BaseModel`

- Abstract application base in practice, although not declared `abstract`.
- Adds unguarded mass assignment, active-session lookup, active-term lookup, next-session creation, admission-letter access, and QR generation.
- `currentSession()` expects exactly one `academic_sessions.status = 'Active'` row.
- `currentSessionTerm()` follows the active session and its active `AcademicSessionTerm`.

### `User` → `users`

- Core fields: `name`, unique `email`, password and two-factor fields, legacy `role`, `status`, profile photo, remember token, current team.
- Relations: one `Teacher`, many `UserInvoice` (legacy), many `CardRequest`, one `App` (legacy), polymorphic many-to-many `Role` and `Permission`.
- Authentication/RBAC methods: `hasAccessRole`, `hasAnyAccessRole`, `hasPermission`, `hasAnyPermission`, `isSuperAdmin`, and `usesRole`.
- `users.role` remains a compatibility field. Database-backed roles and direct permissions are authoritative for granular access.

### `Role` → `roles`

- Fields: unique `name`, unique `slug`, nullable `description`.
- Relations: many-to-many `Permission`; polymorphic many-to-many `User` through `model_has_roles`.

### `Permission` → `permissions`

- Fields: unique `name`, unique `slug`, nullable `description`.
- Relations: many-to-many `Role`; polymorphic many-to-many `User` through `model_has_permissions`.

### `RoleHasPermission` → `role_has_permissions`

- Explicit pivot model for composite key `(role_id, permission_id)`.
- Both columns have enforced foreign keys.

### `ModelHasRole` → `model_has_roles`

- Polymorphic pivot with composite key `(role_id, model_type, model_id)`.
- `role_id` is constrained; the polymorphic target cannot be constrained by MySQL.

### `ModelHasPermission` → `model_has_permissions`

- Polymorphic pivot with composite key `(permission_id, model_type, model_id)`.
- `permission_id` is constrained; the target is logical.

## 5. Academic calendar and school structure

### `AcademicSession` → `academic_sessions`

- Fields: `name`, `status`, application/interview date windows, `form_fee`, session `start_at` and `end_at`.
- Relations: many session terms, repeating records, graduations, students, reserved admission numbers, applications, and invoices.
- Behavior: activates the next term/session, determines whether applications are open, and exposes admission counts.
- Dates and form fee are strings in the live schema.

### `Term` → `terms`

- Field: `name`.
- Relations: class payments, student payments, and subject-teacher uploads.
- The upload relationship method is historically named `subjectTeacherTermylUpload`.

### `AcademicSessionTerm` → `academic_session_terms`

- Fields: logical `term_id`, logical `academic_session_id`, `status`, string `start_at`, string `end_at`.
- Relations: belongs to `AcademicSession` and `Term`; has many `SectionClassStudentTerm` and `SubjectTeacherTermlyUpload`.
- `countDown()` calculates time remaining until `end_at`.
- This is the required bridge when resolving a student term.

### `Section` → `sections`

- Fields: `name`, numeric `level`, `class_tag`, nullable `duration`.
- Relations: many classes, psychomotors, affective traits, and graduation records.
- Behavior: aggregates students, payments, sales, result-upload states, teachers, and published results across its classes; generates required class definitions and year-sequence labels.

### `SectionClassGroup` → `section_class_groups`

- Field: `name` (for example a stream/group label).
- Relation: many `SectionClass`.

### `ResultType` → `result_types`

- Field: `name`.
- Relation: many `SectionClass`.
- Used by class result logic to decide how position/remarks are represented.

### `SectionClass` → `section_classes`

- Fields: `name`, nullable `pass_mark` (default 50), `section_id`, nullable `capacity`, `year_sequence`, `code`, nullable `section_class_group_id`, nullable `result_type_id` (default 1).
- Relations: belongs to section/group/result type; has many students, payment definitions, fee definitions, subjects, class teachers, reserved admission numbers, and termly exams.
- Major behavior groups:
  - student counts, gender counts, current students, admission-number generation and reservation;
  - nextless promotion/next-class resolution;
  - fee calculation by term/student/gender;
  - subject and teacher allocation reporting;
  - result availability, averages, positions, publishing readiness, and upload status summaries.
- Most legacy relationship columns are logical rather than constrained.

### `SectionClassTeacher` → `section_class_teachers`

- Fields: logical `section_class_id`, logical `teacher_id`, `status`.
- Relations: belongs to class and teacher.
- Represents class-master/class-teacher assignment history.

### `SectionClassTermlyExam` → `section_class_termly_exams`

- Fields: logical `academic_session_id`, `section_class_id`, `academic_session_term_id`, and `status`.
- Relations: session, class, session-term, and many legacy exam question sections.

### `SectionClassReservedAdmissionNo` → inferred `section_class_reserved_admission_nos`

- Declares session and class relationships and is used by admission-number reservation logic.
- **No corresponding table exists in the live schema.**

## 6. Students, guardians, enrolment, promotion, and graduation

### `Guardian` → `guardians`

- Fields: nullable `name`, required `phone`, nullable `address`, nullable `email`, `status` defaulting to lowercase `pending`.
- Relation: many `Student`.
- Guardian identity is not linked directly to `users` in the current model.

### `Gender` → `genders`

- Field: `name`.
- Relations: many students and legacy staff.

### `Student` → `students`

- Fields: nullable `guardian_id`, picture, intake `academic_session_id`, nullable desired class, `admission_status`, nullable gender/LGA, `name`, string date of birth, nullable admission number.
- Relations: many enrolments, graduations and promotions; belongs to LGA, desired class, guardian, and gender.
- `sectionClass()` uses the conventional `section_class_id`, but the live table has `desired_section_class_id` instead. Use `desiredSectionClass()` for that live column.
- Behavior: current class/session-term lookup, profile image, initial class assignment, and class repetition.

### `SectionClassStudent` → `section_class_students`

- Fields: logical `section_class_id`, `student_id`, `academic_session_id`, and `status` default `Active`.
- Relations: belongs to class and student; has many terms, repetitions, legacy student-payment rows, and payments.
- This is the authoritative enrolment record for a student in a class during one academic session.
- Behavior: creates missing term rows, resolves current/next term, calculates expected and obtained result scores, fees/payment mode, promotions, discounts, and uploaded-result totals.

### `SectionClassStudentTerm` → `section_class_student_terms`

- Fields: logical `section_class_student_id`, logical `academic_session_term_id`, `status`, nullable `access_code`.
- Relations: belongs to enrolment and session-term; has many results; has one assessment, published summary, and legacy invoice.
- Behavior: access-code generation, publishing, fee/invoice calculations, averages/totals, and `reportResults()`.
- `reportResults()` is the current report/audit source: it matches upload session and term, removes duplicate subject rows, and chooses the highest valid row per subject.
- Correct term access: `$studentTerm->academicSessionTerm->term`.

### `StudentPromotion` → `student_promotions`

- Fields: constrained student, from/to enrolments, nullable promoting/cancelling users, nullable cancellation timestamp.
- Relations: student, source enrolment, destination enrolment, promoted-by user, cancelled-by user.
- Provides an auditable, reversible promotion history.

### `SectionClassStudentRepeating` → `section_class_student_repeatings`

- Fields: logical enrolment and session IDs.
- Relations: belongs to `AcademicSession` and `SectionClassStudent`.
- Records deliberate class repetition in a later session.

### `SectionStudentGraduation` → `section_student_graduations`

- Fields: logical section, student, and session IDs.
- Relations: belongs to each of those models.
- Records completion/graduation from a section.

### `StudentType` → inferred `student_types`

- Declares class-payment and student relations.
- Used by legacy fee calculations.
- **No corresponding table exists in the live schema.**

## 7. Subjects, allocations, uploads, results, and report cards

### `Subject` → `subjects`

- Field: `name`.
- Relation: many class subject offerings.

### `SectionClassSubject` → `section_class_subjects`

- Fields: logical class and subject IDs, denormalized `name`, `status` default `Active`.
- Relations: class, subject, teacher allocations, downloads, uploads, questions, and legacy exam sections.
- Behavior: current exam/question sections, session-term results, upload discovery, and active teacher assignment.

### `SectionClassSubjectTeacher` → `section_class_subject_teachers`

- Fields: logical `teacher_id`, logical `section_class_subject_id`, `status`.
- Relations: teacher, class subject, and many termly uploads.
- Behavior: upload status, downloadable filename, current upload, and session/term result retrieval.

### `SubjectTeacherTermlyUpload` → `subject_teacher_termly_uploads`

- Fields: logical allocation ID, `term_id`, `academic_session_id`, nullable `average`, `level`, numeric/boolean `status`.
- Relations: allocation, term, and many student results.
- **Model caveat:** `academicSessionTerm()` is stale. The original column was renamed from `academic_session_term_id` to `academic_session_id`; the live value points to `AcademicSession`, not `AcademicSessionTerm`.
- Behavior: upload average, completion level, grade distribution, possible/actual score calculations, effectiveness inputs, and subject position.
- Upload status is used as a workflow number (for example in progress/submitted/published), despite being a boolean column in the live schema.

### `StudentResult` → `student_results`

- Fields: logical student-term ID, logical upload ID, string `first_ca`, `second_ca`, `assignment`, `exam`, `total`, and `grade`.
- Relations: belongs to student-term and subject upload.
- `updateTotalAndComputeGrade()` calculates `first_ca + second_ca + assignment + exam`, then maps the total through grade scales.
- `effort()` and `remark()` map the stored grade through remark scales.
- Scores are strings in MySQL; calculations should cast them to numeric types.

### `SectionClassStudentTermResultPublish` → `section_class_student_term_result_publishes`

- Fields: logical student-term ID; nullable string class average, student average, total marks, obtained marks, and position.
- Relation: belongs to student-term.
- `updatePublishRecord()` snapshots report aggregates. These snapshots can drift from underlying results if scores are edited later; the Result Audit screen detects that condition.

### `SectionClassStudentTermAccessment` → `section_class_student_term_accessments`

- Fields: logical student-term, teacher-comment and head-comment IDs; nullable days open/present/absent.
- Relations: student-term, both comment lookups, many affective ratings, and many psychomotor ratings.
- Historical spelling `Accessment` is part of the table/model API.

### `AffectiveTrait` → `affective_traits`

- Fields: nullable logical `section_id`, `name`, boolean `status`.
- Relation: belongs to section.

### `Psychomotor` → `psychomotors`

- Fields: nullable logical `section_id`, `name`, boolean `status`.
- The model currently declares no section relationship, although `section_id` exists and application code uses it.

### `SectionClassStudentTermAccessmentAffectiveTrait` → `section_class_student_term_accessment_affective_traits`

- Fields: logical assessment ID, logical trait ID, nullable integer rating `value`.
- Relations: assessment and trait.
- `getAffectiveTrait()` mutates missing trait IDs by randomly assigning a trait. A read path can therefore write data and should be treated cautiously.

### `SectionClassStudentTermAccessmentPsychomotor` → `section_class_student_term_accessment_psychomotors`

- Fields: logical assessment ID, logical psychomotor ID, nullable integer rating.
- Relations: assessment and psychomotor.
- `getPsychomotor()` similarly fills missing IDs randomly from the student's section.

### `TeacherComment` → `teacher_comments`

- Fields: `name`, integer `gender`.
- Relation: many assessments.

### `HeadTeacherComment` → `head_teacher_comments`

- Fields: `name`, integer `gender`.
- Relation: many assessments.

### `GradeScale` → `grade_scales`

- Fields: nullable logical `section_id`, `grade`, integer lower bound `from`, upper bound `to`.
- Used to compute result grades.
- No relationships are declared despite the section column.

### `RemarkScale` → `remark_scales`

- Fields: `grade`, string `percent`, `remark`, `scale`, nullable logical `section_id`.
- Used for effort, narrative remarks, and some non-ranking result types.

### `SectionClassSubjectDownloads` → `section_class_subject_downloads`

- Fields: logical class-subject and academic-session-term IDs.
- Intended relations: class subject and session-term.
- **Model defect:** relation references misspelled `SectionClassSubjet`, which does not exist.

### `SectionClassSubjectUploads` → `section_class_subject_uploads`

- Same logical fields and purpose as downloads, for uploaded sheets.
- **Model defect:** also references misspelled `SectionClassSubjet`.

## 8. Teachers and qualifications

### `Teacher` → `teachers`

- Fields: logical user/LGA IDs, phone, address, string date of birth, and nullable HR fields: marital status, appointment date, appointment/present grade levels, comment, TRCN.
- Relations: belongs to user and LGA; has many class assignments, qualifications, and subject assignments.
- Accessors expose state ID and user status.
- Behavior aggregates allocation and upload workflow counts.

### `Qualification` → `qualifications`

- Fields: logical `teacher_id`, `name`, nullable uploaded `file`.
- Relation: belongs to teacher.
- `viewQualification()` resolves the stored file for viewing.

## 9. Fees, payments, and payment infrastructure

### `Fee` → `fees`

- Field: `name`.
- Declared relation `feeItems()` points to `FeeItem`, but that model does not exist. The operational design appears to use `SectionClassFee` and `SectionClassFeeItem` instead.

### `SectionClassFee` → `section_class_fees`

- Fields: logical class and fee IDs.
- Relations: fee, class, many fee items, and many payments.
- Represents a fee category enabled for a class.

### `SectionClassFeeItem` → `section_class_fee_items`

- Fields: logical class-fee ID, nullable gender and term IDs, string amount, description.
- Relations: class fee, term, gender.
- Nullable gender allows a fee item to apply to all genders.

### `Payment` → `payments`

- Fields: logical enrolment, class-fee, session, term, and user IDs; `mode`; string amount/date; nullable indexed receipt-group UUID.
- Relations: class fee, enrolment, collecting user, term, session.
- Multiple payment lines can share `receipt_group` as one receipt/collection event.
- No live foreign keys enforce the logical references.

### `AdvancePayment` → `advance_payments`

- Fields: constrained student/class/fee/session/term; optional user/applied payment; receipt group; decimal amount/applied amount; mode/date/status.
- Relations mirror all references.
- `remaining_amount` is computed as `amount - applied_amount`.
- Represents money collected for a future session/term and later converted into a normal payment.

### `SectionClassPayment` → `section_class_payments`

- Legacy class-level charge definition with logical class, term, gender, and student-type IDs plus name and string amount.
- Relations exist for all four lookups.

### `SectionClassStudentPayment` → `section_class_student_payments`

- Legacy per-enrolment payment row with logical enrolment, term, student type, string amount, and mode.
- Relations: term and enrolment.

### `FinanceActivityLog` → `finance_activity_logs`

- Fields: nullable logical user, indexed `activity_type`, polymorphic-style reference type/ID, description, decimal amount, JSON metadata.
- Relation: belongs to user.
- Unlike Laravel morph relations, the reference is currently stored as plain fields with no declared `morphTo()`.

### `Card` → `cards`

- Stores tokenized payment-card metadata: first six/last four digits, issuer, country, type, token, expiry.
- Relation: many transactions.
- Never store full PAN/CVV in this table.

### `Transaction` → `transactions`

- Fields: logical card and nullable invoice IDs; gateway references; monetary response values; currency, response/status/payment metadata.
- Relations: belongs to legacy invoice and card.
- Behavior: determines paid state and whether created today.
- Most amounts are strings because the row mirrors gateway payloads.

### `Token` → `tokens`

- Fields: logical guardian and transaction IDs, nullable PIN, status default lowercase `active`.
- Relations: guardian and one legacy application.
- Represents paid admission/application access rather than API authentication.

### `Invoice`, `UserInvoice`, and `Rank`

- These models implement legacy invoices, user/rank allocation, charges, transaction verification, numbering, and payable totals.
- **Their inferred tables (`invoices`, `user_invoices`, `ranks`) do not exist in the live schema.**
- Current features should not depend on them without restoring their migrations/tables.

## 10. Inventory

### `InventoryCategory` → `inventory_categories`

- Fields: `name`, nullable description.
- Relation: many items.

### `InventoryItem` → `inventory_items`

- Fields: nullable constrained category, unique SKU, name/description, decimal unit cost/selling price, aggregate quantity, soft-delete timestamp.
- Relations: category, transactions, stock batches.
- Soft deletes preserve historical sale/usage references.

### `InventoryStock` → `inventory_stocks`

- Fields: constrained item, received and remaining quantity, cost and selling prices, received date, notes.
- Relations: item and sale items.
- Represents a stock batch; `remaining_quantity` is the batch balance.

### `InventoryTransaction` → `inventory_transactions`

- Fields: constrained item, transaction type, quantity, unit cost, notes, transaction date.
- Relation: item.
- General stock movement ledger.

### `InventoryUsage` → `inventory_usages`

- Fields: constrained item; optional constrained batch, student enrolment, or teacher; enum `usage_type` (`sale`/`rent`); quantity/cost totals; receipt/evidence/date/notes.
- Relations: item, stock, student enrolment, teacher.
- Supports the older combined usage workflow alongside dedicated sale/rent tables.

### `InventorySale` → `inventory_sales`

- Fields: constrained student enrolment, decimal total cost, payment method, evidence, usage date, notes.
- Relations: many sale items; belongs to `SectionClassStudent` through a method named `student()`.
- The relation name can be misleading: it returns an enrolment, not a `Student`.

### `InventorySaleItem` → `inventory_sale_items`

- Fields: constrained sale/item, optional stock batch, quantity, selling unit cost, cost price, line amount.
- Relations: sale, item, stock batch.
- Computed `profit` uses sale amount versus cost price/quantity.

### `InventoryRent` → `inventory_rents`

- Live fields include constrained item/teacher, quantity, usage date, notes, plus session/return/status fields retained in the current database.
- Relations: item, teacher, academic session.
- **Migration drift:** the down path of `extend_finance_workflows` suggests those return/session columns were intended for removal, but they remain live and the model still uses `academicSession()`.

## 11. Reporting and analytics

### `TermlyTeacherEffectiveIndex` → `termly_teacher_effective_indices`

- Dimensions: session, teacher, term, section.
- Measures: total students/subjects/classes, obtained/possible totals, effectiveness index, average class score.
- Relations declared for teacher and term; session/section relationships are not declared.

### `TeachersClassSubjectComparison` → `teachers_class_subject_comparisons`

- Dimensions: constrained section, session, teacher, class, subject, term.
- Measures: students, obtained/possible totals, percentage, teacher effectiveness index.
- Relations: teacher, subject, class, term; no declared session/section relation.

### `TermlySubjectEvaluation` → `termly_subject_evaluations`

- Dimensions: constrained subject, class, term, session; logical section.
- Measures: student count, totals, average, average teacher effectiveness.
- Relations: subject, class, term.

### `TermlyClassAveraging` → `termly_class_averagings`

- Dimensions: constrained class/session/term; logical section.
- Measures: student/subject counts, totals, class average, average teacher effectiveness.
- Relations: class, session, term.

### `CentralAndDisperseResultMeasure`

- Declares a subject-upload relation and represents central-tendency/dispersion analytics.
- Its migration intentionally comments out table creation, and no live table exists.

## 12. Geography, configuration, and admissions support

### `State` → `states`

- Field: `name`.
- Relation: many LGAs.
- Also references `NeighboringStateDiscount`, which has no model/table in the current repository/live schema.

### `Lga` → `lgas`

- Fields: logical `state_id`, `name`.
- Relation: belongs to state.

### `AdmissionLetter` → `admission_letters`

- Stores configurable text fragments for heading, introduction, payment note, and congratulatory note.
- Single-row configuration semantics are assumed by `BaseModel::admissionLetter()`.

### `CardRequest` → `card_requests`

- Fields: constrained user, nullable logical section, position/reason/signature/staff ID, status default `Pending`.
- Relations: user and section.
- Only `user_id` is currently enforced by MySQL.

### `Interview` → `interviews`

- Fields: English, maths and Arabic scores plus logical application ID.
- Relation: belongs to legacy application.
- The table exists, but the parent `applications` table does not exist in the live schema.

## 13. Legacy/inactive model inventory

The following models have no inferred table in the current live database. They document an older admission, staff, examination, or application subsystem and may still be referenced by dormant code.

### Application and applicant profile

- `Application`: educational records, Quran knowledge, language proficiencies, medical records, token, LGA, interview, and applicant image.
- `EducationalRecord`: applicant education history.
- `QuranKnowledge`: parent for recitation participation.
- `Language`, `LanguageProficiency`: applicant language catalogue/proficiency.
- `MedicalRecord`, `Disease`: applicant medical information. `MedicalRecord::application()` currently incorrectly belongs to `MedicalRecord` itself.
- `Certification`: legacy certificate record.
- `RecitationParticipation`, `RecitationParticipationLevel`: Quran recitation participation lookups.

### Legacy online examination

- `ExamSubjectQuestionSection`: joins a class exam and class subject and owns questions.
- `Question`: belongs to type/exam section/class subject and owns options/items.
- `QuestionType`: question-type lookup.
- `QuestionItem`: question sub-item.
- `Option`: possible answer option.

### Staff/application configuration

- `Staff`: links guardian, gender, and user.
- `StaffCode`: one-to-one staff code model.
- `App` and `AppColor`: per-user application configuration; `App::user()` appears incorrectly declared as `belongsTo(App::class)`.

These models should either receive new migrations and tests or be explicitly retired to reduce uncertainty.

## 14. Framework tables without domain models

- `sessions`: Laravel database session storage.
- `password_resets`: password reset tokens.
- `personal_access_tokens`: Laravel Sanctum API tokens.
- `failed_jobs`: failed queue jobs.
- `migrations`: Laravel migration history.

## 15. Result lifecycle walkthrough

1. A reusable `Term` is attached to an `AcademicSession` through `AcademicSessionTerm`.
2. A `Student` receives a `SectionClassStudent` enrolment for that session.
3. One `SectionClassStudentTerm` is created for each session-term.
4. A teacher is allocated through `SectionClassSubjectTeacher`.
5. The teacher creates/submits a `SubjectTeacherTermlyUpload` for a session and term.
6. Each score becomes a `StudentResult`, linking the student-term to the subject upload.
7. Assessment attendance, comments, affective traits, and psychomotor ratings are stored under `SectionClassStudentTermAccessment`.
8. Publishing creates/updates `SectionClassStudentTermResultPublish` and changes upload workflow state.
9. An access code on `SectionClassStudentTerm` allows guardian-facing result lookup.
10. The report and Result Audit use `SectionClassStudentTerm::reportResults()` to enforce exact session/term matching and one result per subject.

## 16. Finance lifecycle walkthrough

1. A `Fee` is enabled for a class through `SectionClassFee`.
2. `SectionClassFeeItem` defines term/gender-specific amounts.
3. A payment line records enrolment, fee, session, term, collector, mode, amount, and date.
4. Lines sharing `receipt_group` form one receipt.
5. Advance payments remain pending until applied to a future term, at which point `applied_payment_id` links to the created payment.
6. `FinanceActivityLog` records operational actions independently of payment rows.

## 17. Referential-integrity and correctness recommendations

Priority improvements derived from the live schema review:

1. Add foreign keys and indexes to the legacy academic/result tables after first detecting and repairing orphan rows.
2. Change score and monetary strings to appropriate decimal columns and date strings to `date`/`datetime` columns.
3. Add unique constraints for natural identities, especially:
   - `(academic_session_id, term_id)` on `academic_session_terms`;
   - `(student_id, section_class_id, academic_session_id)` on enrolments where appropriate;
   - `(section_class_student_id, academic_session_term_id)` on student terms;
   - one result per `(section_class_student_term_id, subject allocation/upload)`;
   - one published summary and one assessment per student-term.
4. Replace random mutations in `getAffectiveTrait()` and `getPsychomotor()` with explicit repair commands.
5. Fix stale/broken relationships: upload academic session, `SectionClassSubjet`, `MedicalRecord::application`, `App::user`, and `Fee::feeItems`.
6. Standardize status values with constants/enums and document allowed transitions.
7. Decide whether absent legacy tables should be restored or their models/routes removed.
8. Reconcile the live `inventory_rents` columns with the migration intent before future schema changes.
9. Avoid hard deletion of parent records until legacy foreign keys are enforced; prefer deactivation or soft deletion.
10. Add model-level casts for numeric scores, amounts, dates, booleans, JSON metadata, and timestamps.

## 18. Safe query examples

### Students in a class for a session and term

```php
SectionClassStudent::query()
    ->where('section_class_students.academic_session_id', $sessionId)
    ->where('section_class_students.section_class_id', $classId)
    ->with(['student.guardian', 'sectionClassStudentTerms' => function ($query) use ($sessionId, $termId) {
        $query->whereHas('academicSessionTerm', function ($query) use ($sessionId, $termId) {
            $query->where('academic_session_id', $sessionId)
                ->where('term_id', $termId);
        });
    }])
    ->get();
```

### Correct term access

```php
$session = $studentTerm->academicSessionTerm->academicSession;
$term = $studentTerm->academicSessionTerm->term;
```

### Report-card results

```php
$results = $studentTerm->reportResults();
$obtained = $results->sum(fn ($result) => (float) $result->total);
$possible = $results->count() * 100;
```

This uses the exact selected session/term and prevents duplicate subject display.
