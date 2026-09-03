<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncLegacyRbac extends Command
{
    protected $signature = 'rbac:sync-legacy
                            {--execute : Persist the planned changes (without this flag the command is a dry run)}';

    protected $description = 'Preview or import the hardcoded legacy roles into the database-backed access-control tables';

    private const ROLES = [
        'superadmin' => ['Super Administrator', 'Full access to every database-backed permission'],
        'admin' => ['Administrator', 'System users, card requests and access-control administration'],
        'head' => ['Head of School', 'School oversight, administration, sections and academic configuration'],
        'admission_officer' => ['Admission Officer', 'Student admission and enrolment operations'],
        'exam_officer' => ['Examination Officer', 'Examination uploads, results, publishing and access codes'],
        'finance_officer' => ['Finance Officer', 'Fees, payments, reports and inventory operations'],
        'patron' => ['Patron', 'Institution-wide statistics plus finance and inventory oversight'],
        'teacher' => ['Teacher', 'Assigned classes, students, assessments, attendance and scores'],
        'guardian' => ['Guardian', 'Guardian account with no staff administration privileges'],
        'staff' => ['Staff', 'General staff account with no module-specific privileges'],
    ];

    private const PERMISSIONS = [
        'manage-users' => ['Manage users', 'View and maintain system user accounts'],
        'manage-card-requests' => ['Manage card requests', 'Review, update, print and remove ID card requests'],
        'manage-access-control' => ['Manage access control', 'Maintain roles and permissions'],
        'manage-calendar' => ['Manage calendar', 'Maintain academic sessions, terms and calendar settings'],
        'manage-teachers' => ['Manage teachers', 'Create, update and maintain teacher records'],
        'manage-subjects' => ['Manage subjects', 'Create, allocate and maintain subjects'],
        'manage-classes' => ['Manage classes', 'Create, update and maintain classes'],
        'manage-grading-scales' => ['Manage grading scales', 'Create and maintain report grading scales'],
        'manage-remark-scales' => ['Manage remark scales', 'Create and maintain report remark scales'],
        'manage-comments' => ['Manage comments', 'Create and maintain teacher and head-teacher comments'],
        'manage-class-subjects' => ['Manage class subjects', 'Create and maintain subject offerings for classes'],
        'manage-class-teacher-allocation' => ['Manage class teacher allocation', 'Assign and maintain class teachers'],
        'manage-result-uploads' => ['Manage result uploads', 'Review, update and manage uploaded results'],
        'manage-sales' => ['Manage sales', 'Create and maintain inventory sales'],
        'manage-rents' => ['Manage rents', 'Create and maintain inventory rentals'],
        'manage-students' => ['Manage students', 'Create and maintain student records'],
        'send-notifications' => ['Send notifications', 'Send school notifications to account groups'],
        'manage-school-results' => ['Manage school results', 'Review and oversee results across the school'],
        'view-student-results' => ['View student results', 'Search student results and download report cards'],
        'view-result-access-codes' => ['View result access codes', 'Search students and view result access codes'],
        'manage-sections' => ['Manage sections', 'Maintain sections, classes, students and class allocation'],
        'manage-school-administration' => ['Manage school administration', 'Maintain sessions, calendars, teachers and staff'],
        'manage-report-card-configuration' => ['Manage report-card configuration', 'Maintain grades, remarks, traits and report-card text'],
        'manage-admissions' => ['Manage admissions', 'Create, update, import and remove admitted students'],
        'manage-examinations' => ['Manage examinations', 'Review uploads, edit results, publish results and issue access codes'],
        'manage-fees' => ['Manage fees', 'Configure class fee items'],
        'manage-payments' => ['Manage payments', 'Record payments, issue receipts and produce payment reports'],
        'manage-inventory' => ['Manage inventory', 'Maintain inventory items, stock, sales, rents and usage'],
        'view-school-analytics' => ['View school analytics', 'View institution-wide analysis and statistics'],
        'manage-assigned-classes' => ['Manage assigned classes', 'View assigned classes, students and subjects'],
        'manage-assessments' => ['Manage assessments', 'Maintain assessments and attendance for assigned classes'],
        'manage-scores' => ['Manage scores', 'Enter and submit scores for assigned subjects'],
    ];

    private const ROLE_PERMISSIONS = [
        'superadmin' => [], // Populated with every permission during synchronization.
        'admin' => [
            'manage-users', 'manage-card-requests', 'manage-access-control',
            'manage-calendar', 'manage-teachers', 'manage-sections', 'manage-subjects',
            'manage-classes', 'manage-grading-scales', 'manage-remark-scales', 'manage-comments',
        ],
        'head' => [
            'send-notifications', 'manage-school-results', 'manage-sections', 'manage-school-administration', 'manage-report-card-configuration',
            'manage-teachers', 'manage-class-subjects', 'manage-class-teacher-allocation', 'manage-result-uploads', 'manage-inventory',
            'manage-payments', 'manage-sales', 'manage-rents', 'manage-students', 'view-student-results', 'view-result-access-codes',
        ],
        'admission_officer' => ['manage-admissions'],
        'exam_officer' => ['manage-examinations', 'view-student-results', 'view-result-access-codes'],
        'finance_officer' => ['manage-fees', 'manage-payments', 'manage-inventory'],
        // FinanceOfficerMiddleware explicitly accepts patrons, and the patron menu exposes finance/inventory reports.
        'patron' => ['manage-fees', 'manage-payments', 'manage-inventory', 'view-school-analytics'],
        'teacher' => ['manage-assigned-classes', 'manage-assessments', 'manage-scores'],
        'guardian' => [],
        'staff' => [],
    ];

    public function handle()
    {
        if (!$this->schemaIsReady()) {
            return 1;
        }

        $execute = (bool) $this->option('execute');
        $legacyCounts = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderBy('role')
            ->pluck('total', 'role');

        $roleDefinitions = $this->roleDefinitions($legacyCounts->keys()->all());
        $newRoles = collect($roleDefinitions)->keys()->diff(Role::pluck('slug'));
        $newPermissions = collect(self::PERMISSIONS)->keys()->diff(Permission::pluck('slug'));
        $usersWithoutMappedRole = User::whereDoesntHave('accessRoles', function ($query) {
            $query->whereColumn('roles.slug', 'users.role');
        })->count();

        $this->info($execute ? 'EXECUTE MODE' : 'DRY RUN — no database changes will be made');
        $this->newLine();
        $this->table(['Item', 'Defined', 'To create'], [
            ['Roles', count($roleDefinitions), $newRoles->count()],
            ['Permissions', count(self::PERMISSIONS), $newPermissions->count()],
            ['Role-permission grants', collect(self::ROLE_PERMISSIONS)->flatten()->count() + count(self::PERMISSIONS), 'Missing grants will be added'],
            ['Existing users', $legacyCounts->sum(), $usersWithoutMappedRole.' need a matching database role'],
            ['Superadmin account', 1, User::where('email', 'isahlabbo@fayis.ng')->exists() ? 'Will be refreshed' : 'Will be created'],
        ]);

        $this->table(['Legacy role', 'Users', 'Mapped database role'], $legacyCounts->map(function ($total, $role) {
            return [$role ?: '(empty)', $total, $this->normaliseRoleSlug($role)];
        })->values()->all());

        if (!$execute) {
            $this->comment('Run `php artisan rbac:sync-legacy --execute` to apply this plan.');
            return 0;
        }

        $result = DB::transaction(function () use ($roleDefinitions) {
            $roles = [];
            foreach ($roleDefinitions as $slug => [$name, $description]) {
                $roles[$slug] = Role::updateOrCreate(['slug' => $slug], compact('name', 'description'));
            }

            $permissions = [];
            foreach (self::PERMISSIONS as $slug => [$name, $description]) {
                $permissions[$slug] = Permission::updateOrCreate(['slug' => $slug], compact('name', 'description'));
            }

            $grantCount = 0;
            foreach (self::ROLE_PERMISSIONS as $roleSlug => $permissionSlugs) {
                if ($roleSlug === 'superadmin') {
                    $permissionSlugs = array_keys($permissions);
                }
                $ids = collect($permissionSlugs)->map(fn ($slug) => $permissions[$slug]->id)->all();
                $before = $roles[$roleSlug]->permissions()->count();
                $roles[$roleSlug]->permissions()->syncWithoutDetaching($ids);
                $grantCount += $roles[$roleSlug]->permissions()->count() - $before;
            }

            $userCount = 0;
            User::query()->orderBy('id')->chunkById(200, function ($users) use (&$userCount, $roles) {
                foreach ($users as $user) {
                    $slug = $this->normaliseRoleSlug($user->role);
                    if (!$user->accessRoles()->where('roles.id', $roles[$slug]->id)->exists()) {
                        $user->accessRoles()->attach($roles[$slug]->id);
                        $userCount++;
                    }
                }
            });

            $superadmin = User::updateOrCreate(
                ['email' => 'isahlabbo@fayis.ng'],
                [
                    'name' => 'Isah Labbo',
                    'password' => Hash::make('admin@1234'),
                    'role' => 'superadmin',
                    'status' => 'Active',
                ]
            );
            $superadmin->forceFill(['email_verified_at' => now()])->save();
            if (!$superadmin->accessRoles()->where('roles.id', $roles['superadmin']->id)->exists() || $superadmin->accessRoles()->count() !== 1) {
                $superadmin->accessRoles()->sync([$roles['superadmin']->id]);
                $userCount++;
            }

            return ['grants' => $grantCount, 'users' => $userCount];
        });

        $this->newLine();
        $this->info('Legacy RBAC data synchronized successfully.');
        $this->line("Added {$result['grants']} role-permission grants and {$result['users']} user-role assignments.");
        $this->comment('Existing custom roles, permissions and assignments were preserved; users.role was not changed.');

        return 0;
    }

    private function schemaIsReady()
    {
        $tables = ['roles', 'permissions', 'role_has_permissions', 'model_has_roles', 'model_has_permissions'];
        $missing = collect($tables)->reject(fn ($table) => Schema::hasTable($table));

        if ($missing->isNotEmpty()) {
            $this->error('RBAC tables are missing: '.$missing->implode(', '));
            $this->line('Run `php artisan migrate` before running this command.');
            return false;
        }

        return true;
    }

    private function roleDefinitions(array $legacyRoles)
    {
        $roles = self::ROLES;
        foreach ($legacyRoles as $legacyRole) {
            $slug = $this->normaliseRoleSlug($legacyRole);
            if (!isset($roles[$slug])) {
                $roles[$slug] = [Str::title(str_replace(['-', '_'], ' ', $legacyRole ?: 'unassigned')), 'Imported from the legacy users.role column'];
            }
        }

        return $roles;
    }

    private function normaliseRoleSlug($role)
    {
        return Str::slug(Str::lower(trim((string) $role)) ?: 'unassigned', '_');
    }
}
