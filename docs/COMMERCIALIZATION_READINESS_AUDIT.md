# Commercialization Readiness Audit

## Executive decision

**Current decision: not ready for commercial production or multi-school rollout.**

The application has useful functional coverage for a Nigerian school—admissions, student enrolment, academic sessions and terms, classes, subjects, results, report cards, fees, inventory, users, and role-based navigation. It is a credible prototype/pilot foundation. It does not yet meet the reliability, security, configurability, tenancy, data-governance, testing, or operational controls expected of a world-standard school management product.

Indicative readiness score: **38/100**.

| Area | Score | Commercial assessment |
|---|---:|---|
| Functional school coverage | 62/100 | Good core starting point, but important administrative modules and workflows are incomplete |
| Academic adaptability | 45/100 | Sessions, terms, sections and classes exist, but rules are embedded in code and legacy relationships |
| Multi-school adaptability | 15/100 | No enforceable tenant/school boundary or per-school configuration model |
| Database integrity | 30/100 | Broad schema, but too few foreign keys and many unsuitable data types and stale relationships |
| Security and privacy | 32/100 | Authentication/RBAC exists, but production configuration and route semantics are unsafe |
| Reliability and testing | 20/100 | Tests are not isolated and can reset the normal database; known failures exist |
| Operations and supportability | 25/100 | No demonstrated backup/restore, monitoring, deployment, SLA, or disaster-recovery discipline |
| Reporting and interoperability | 40/100 | Report-card capability exists, but exports, APIs, auditability and integrations need strengthening |

The score is an engineering judgement based on source inspection, migrations, models, routes, configuration, automated-test behaviour, and the available database dump. It is not a certification.

## Scope and method

The audit reviewed the Laravel application structure, 96 model classes, 92 migrations, 75 current application tables, route definitions, authorization approach, test configuration, dependency posture, report/result architecture, finance and inventory areas, and the available SQL backup. A model-by-model reference is maintained separately in `docs/DATABASE_MODEL_REFERENCE.md`.

Live data-quality conclusions are limited because the development database was reset by the test suite during this audit. The incident and recovery status are documented below. No live replacement has been performed.

## Major strengths

1. The domain model recognizes important Nigerian school concepts: academic sessions, session terms, sections, classes, enrolments, subject allocation, continuous assessment, examinations, affective traits, psychomotor traits, comments, publication, promotion and graduation.
2. Results are associated through `SectionClassStudentTerm`, which is the correct type of historical anchor for a learner in a class and academic-session term. Term resolution should consistently follow `sectionClassStudentTerm -> academicSessionTerm -> term`.
3. Role and permission infrastructure is present and has recently been extended for examination officers and heads of school.
4. The product already includes finance, admissions and inventory foundations instead of being only an electronic report-card application.
5. Migrations and Eloquent models provide a path toward repair without a total rewrite.

## Critical release blockers

### 1. Tests can erase the normal database

The PHPUnit SQLite/testing connection settings are commented out, so `RefreshDatabase` uses the configured MySQL database. A test run issued a single `DROP TABLE` statement against all application tables. Similar resets also appear in retained August binary logs.

Required control:

- Create a dedicated `.env.testing` with a separate database whose name must contain `_test`.
- Add a bootstrap guard that aborts tests unless `APP_ENV=testing` and the database name passes that rule.
- Remove access to production credentials from CI and developer test processes.
- Test backup restoration regularly, not merely backup creation.

This is a **commercialization stop-ship issue**.

### 2. No multi-school tenancy boundary

Core records do not consistently carry `school_id` or `tenant_id`, and there is no global tenant scope, tenant-aware authorization, tenant-specific storage, or unique constraints scoped to a school. Configuration such as grading, terms, sections and fees is effectively global.

Consequences include cross-school data exposure, conflicting admission numbers, incompatible calendars, and inability to let two customers configure the product independently.

Before SaaS rollout, introduce `schools`, `campuses`, memberships, tenant-scoped roles, tenant-aware uniqueness and indexes, tenant-safe queues/files/cache, and automated cross-tenant isolation tests. A separate database per school is also viable, but must be an explicit supported architecture.

### 3. Production configuration is unsafe

The inspected environment is configured with `APP_ENV=local` and `APP_DEBUG=true`. Queue execution is synchronous and cache is file-based. Debug output can expose personal data and internals. Synchronous report generation and bulk result work will degrade request reliability as usage grows.

Commercial deployments need environment-specific secrets, debug disabled, HTTPS, secure cookies, Redis-backed cache/queues, supervised workers, centralized logs, health checks and repeatable deployment/rollback.

### 4. Database integrity is insufficient

The inspected schema has approximately 134 `_id` columns but only 46 declared foreign keys. Several relationships are therefore conventions rather than enforced guarantees. Scores, money and dates are frequently stored as strings. Model/schema drift and references to absent tables are documented in the model reference.

This makes orphan records, invalid totals, ambiguous joins and silent reporting errors more likely. Financial amounts should use fixed-precision decimals; scores should use constrained numeric columns; dates should use date/time types; status values should be normalized; important natural identities need tenant-scoped unique indexes.

### 5. Unsafe HTTP and authorization patterns

Multiple state-changing actions—including deletion, result publication and return-for-correction—are exposed through GET routes. GET must be safe and idempotent; destructive or workflow-changing actions require POST/PATCH/DELETE, CSRF protection, authorization policies and confirmation where appropriate.

Sidebar visibility is not security. Every controller/Livewire action, download and bulk operation must independently enforce permission and tenant scope.

### 6. Framework and dependency lifecycle

The application is based on Laravel 8 and supports legacy PHP constraints. By 2026 this is outside an acceptable mainstream support posture for a newly commercialized system. Composer validation has also identified an invalid autoload key casing, and the dependency set includes abandoned packages.

Upgrade through supported Laravel/PHP versions, replace abandoned libraries, establish monthly dependency and vulnerability review, and generate a software bill of materials for releases.

## Academic adaptability for Nigerian schools

Nigeria-wide adaptability requires configuration rather than assumptions. Schools vary by state, ownership, faith, curriculum, calendar, assessment scheme, nomenclature and reporting policy. Federal bodies also revise curriculum offerings and quality-assurance expectations.

The following must become per-school and historically versioned:

- academic year structure and arbitrary term/semester names;
- early-years, primary, junior secondary, senior secondary, technical and other section structures;
- class arms/streams and mixed promotion paths;
- curriculum version, subject catalogue, compulsory/elective rules and subject groups;
- assessment components, maximum scores, weightings, rounding and missing-score rules;
- grading scales, remarks, pass marks and promotion rules by level and year;
- position policy: competition ranking, dense ranking, ties, exclusions and privacy;
- report templates, logos, signatures, comments, traits and languages;
- continuous-assessment approval, correction, locking, publication and re-opening workflows;
- transfer-in history, repeats, withdrawal, graduation and alumni records.

The present system models many of these concepts but does not provide a sufficiently general rules engine or versioned configuration. Business logic must not infer a direct `SectionClassStudentTerm->term`; it should resolve through `academicSessionTerm->term` and retain that historical association.

For curriculum evolution, model curriculum editions and effective dates. Never rewrite old report cards when a school changes its grading or subject structure in a later session.

## Administrative adaptability gaps

For broad commercialization, schools will expect configurable workflows beyond the present foundation:

- attendance for learners and staff;
- timetable, rooms, periods and substitutions;
- staff HR, contracts, leave, payroll interfaces and appraisal;
- parent/guardian portal with verified relationships and communication preferences;
- messaging, notices, consent and delivery audit;
- safeguarding, health, incidents and tightly restricted sensitive records;
- transport, hostel/boarding, library and optional modules;
- fee schedules, discounts, scholarships, instalments, refunds, reversals and reconciliation;
- multiple campuses, departments, cost centres and approval limits;
- immutable receipts and finance audit trails;
- configurable admissions forms, entrance assessment and document verification;
- bulk import with validation, dry-run, error report and rollback.

These should be modular capabilities. A school should be able to disable irrelevant modules without code changes.

## Results and report-card integrity

Result correctness is a high-risk domain. The system should calculate from authoritative assessment records, not presentation-layer totals. Recommended invariants include:

- one result per learner-term-subject-assessment definition;
- assessment component score between zero and its configured maximum;
- obtained mark equals the defined component calculation;
- subject total, grade and remark come from the grading policy effective for that session;
- class/subject position is calculated from an explicitly configured ranking policy;
- published results are immutable snapshots or fully versioned revisions;
- every edit, approval, publication, unpublication and download is auditable;
- PDF and screen views consume the same result projection/service;
- comments, traits and psychomotor records are loaded for the selected learner term only.

Model accessors must not write random/default trait data while reading a report. Reads should be deterministic and side-effect free.

## Privacy, security and safeguarding

The platform processes children’s identity, academic, guardian, financial and potentially health information. Commercial operation therefore requires a formal Nigeria Data Protection Act programme, including documented lawful bases, notices, retention, data-subject request handling, processor contracts, breach response and appropriate technical/organizational controls.

Minimum engineering controls:

- least-privilege RBAC plus record/tenant-level authorization;
- MFA for privileged roles;
- encryption in transit and protected backups/object storage;
- short-lived, revocable result-access codes stored as hashes where feasible;
- rate limiting and attempt logging for public result lookup;
- secure file authorization rather than guessable public paths;
- immutable audit records for results, finance, identity and permissions;
- account lifecycle, session revocation and periodic access review;
- retention and secure deletion policies;
- vulnerability scanning, penetration testing and incident-response exercises.

## Engineering and quality findings

- Route enumeration has a pre-existing failure caused by a missing `App\Http\Controllers\SectionClassController`.
- Finance middleware tests fail because their user double does not implement the expected `hasPermission()` contract.
- Some tests contain no assertions or are skipped, limiting their value.
- Mass assignment is broadly open through `BaseModel::$guarded = []`; input DTOs/form requests and explicit model fillable fields are safer.
- Some model methods perform writes during reads, creating non-deterministic behaviour.
- Naming includes legacy misspellings such as `accessment`, increasing integration and maintenance friction.
- Large report/result queries need query-count and performance tests to prevent N+1 behaviour.

Required quality gates should include static analysis, formatting, migration tests, unit/domain tests, feature authorization tests, tenant-isolation tests, browser smoke tests, PDF snapshot/content tests, load tests, dependency audit and backup-restore verification.

## Interoperability and product standards

A world-standard product should provide stable versioned APIs and documented imports/exports. Priorities include CSV/Excel templates, accounting exports, payment-provider reconciliation, SMS/email providers, identity/data export, and standards-based authentication for larger schools. Integrations must use idempotency keys, signed webhooks, retry queues and reconciliation jobs.

Accessibility, responsive design, low-bandwidth behaviour, timezone/currency correctness, localization, printable documents and full keyboard operation should be acceptance criteria—not cosmetic enhancements.

## Commercialization roadmap

### Phase 0 — containment and recovery (immediate)

1. Keep the current empty database and all recovery candidates untouched until a verified restore is selected.
2. Locate any post-September-2025 backup from hosting, cloud storage, Windows backup, another developer machine or production server.
3. Repair test isolation and add the hard database-name guard.
4. Establish encrypted daily backups, off-site retention and quarterly restore drills.

Exit criterion: an approved dataset is restored, reconciled by school owners, and a test can no longer connect to it.

### Phase 1 — production safety (4–8 weeks)

Fix unsafe routes, enforce policies, disable debug, repair route booting, upgrade dependencies, eliminate test failures, add audit logs, normalize critical result/finance types, and document deployment/recovery.

Exit criterion: security review passes; critical workflows have automated tests; staging is isolated and reproducible.

### Phase 2 — configurable single-school product (8–16 weeks)

Introduce versioned academic policies, curriculum editions, configurable report templates, robust approval/publication, finance reconciliation, imports and operational monitoring.

Exit criterion: two materially different Nigerian schools can be configured without source-code changes while preserving prior-session results.

### Phase 3 — multi-school commercialization (12–24 weeks)

Implement the tenancy architecture, tenant billing/support operations, data migration toolkit, SLA monitoring, onboarding, privacy operations and integration APIs.

Exit criterion: automated tests prove cross-tenant isolation and disaster recovery; pilot schools complete a full term and year rollover.

### Phase 4 — world-standard maturity (ongoing)

Pursue independent penetration testing, accessibility conformance, formal secure-development practices, availability objectives, performance baselines, customer support metrics and—where commercially justified—ISO 27001/SOC 2 readiness.

## Go/no-go recommendation

- **Internal development/demo:** go, after restoring safe test isolation.
- **Controlled pilot with synthetic or non-critical data:** conditional go after Phase 1 blockers.
- **Single-school production containing real learner data:** no-go until recovery, security, backups, result integrity and operational controls are verified.
- **Multi-school SaaS commercialization:** no-go until explicit tenancy and configurable academic policy are implemented.
- **Claim of “world-standard” readiness:** no-go; this requires demonstrated controls and operational evidence, not only feature breadth.

## Database recovery note — 3 September 2026

During this audit, `php artisan test` used the normal `fayis` connection because PHPUnit’s isolated database settings were commented out. At 17:30:18 the suite dropped the application tables; the event begins in `binlog.000184` at position `6256877`.

Recovery work has remained isolated:

- `fayis_recovery_20260903`: the September-2025 dump baseline, containing 790 students, 790 enrolments, 2,868 learner-term records and 7 users;
- `fayis_recovery_forced_20260903`: a forensic replay candidate that demonstrates the retained logs include repeated historical test resets and ends with test data, not a credible school dataset;
- `fayis_recovery_safe_20260903`: another baseline candidate; automatic migration stopped safely on migration-history/schema drift.

The current `fayis` database has not been overwritten. The best next recovery source is a newer external backup. In its absence, the September-2025 baseline is the most credible data source, but it cannot recover unlogged changes made after that backup.

## Reference baseline

This assessment aligns the product direction with the Nigeria Data Protection Act obligations described by the [Nigeria Data Protection Commission](https://www.ndpc.gov.ng/faqs/), curriculum revision and offerings published by [NERDC](https://nerdc.gov.ng/) and its [curriculum offerings](https://nerdc.gov.ng/content_manager/pdf_files/Basic%20and%20Senior%20Secondary%20Education%20Curriculum%20Offerings.pdf), and standards/quality-assurance responsibilities described by the [Federal Ministry of Education](https://education.gov.ng/federal-education-quality-assurance-service/). Product-specific legal and compliance review is still required before launch.
