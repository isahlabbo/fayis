@php
    $user = Auth::user();
    $user->loadMissing(['accessRoles.permissions', 'directPermissions']);
    $rolePriority = ['head', 'admin', 'admission_officer', 'exam_officer', 'finance_officer', 'patron', 'teacher', 'guardian', 'staff'];
    $roleLabels = [
        'admin' => 'Administrator', 'head' => 'Head of School', 'admission_officer' => 'Admissions',
        'exam_officer' => 'Examinations', 'finance_officer' => 'Finance', 'patron' => 'Patron',
        'teacher' => 'Teacher', 'guardian' => 'Guardian', 'staff' => 'Staff',
    ];
    $portalRoles = collect($rolePriority)->filter(fn ($role) => $user->usesRole($role));
    if ($portalRoles->isEmpty() && $user->role) $portalRoles->push($user->role);
    $portalRoles = $portalRoles->unique()->values();
    $roleLabel = $portalRoles->map(fn ($role) => $roleLabels[$role] ?? ucwords(str_replace('_', ' ', $role)))->join(' / ');
    $hasFinancePortal = $portalRoles->contains(fn ($role) => in_array($role, ['finance_officer', 'patron'], true));
@endphp

<header class="portal-mobile-bar">
    <button type="button" class="portal-menu-button" aria-label="Open navigation" onclick="document.body.classList.toggle('portal-sidebar-open')"><i class="fas fa-bars"></i></button>
    <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.jpg') }}" alt="FAYIS"><strong>FAYIS</strong></a>
</header>
<button class="portal-sidebar-overlay" aria-label="Close navigation" onclick="document.body.classList.remove('portal-sidebar-open')"></button>

<aside class="portal-sidebar" aria-label="{{ $roleLabel }} navigation">
    <a class="portal-brand" href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.jpg') }}" alt="FAYIS logo"><span><strong>FAYIS</strong><small>{{ $roleLabel }}</small></span></a>
    <nav>
        <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i><span>Dashboard</span></a>

        @if($portalRoles->contains('admin'))
            <div class="portal-nav-title">Administration</div>
            @if($user->hasPermission('manage-users'))
                <a href="{{ route('configuration.users.index') }}"><i class="fas fa-users"></i><span>User Management</span></a>
            @endif
            @if($user->hasPermission('manage-card-requests'))<a class="{{ request()->routeIs('admin.card.*') ? 'active' : '' }}" href="{{ route('admin.card.index') }}"><i class="fas fa-id-card"></i><span>Card Requests</span></a>@endif

            @if($user->hasPermission('manage-calendar') || $user->hasPermission('manage-teachers') || $user->hasPermission('manage-sections') || $user->hasPermission('manage-subjects') || $user->hasPermission('manage-classes'))
                <div class="portal-nav-title">Academics</div>
                <details><summary><span><i class="fas fa-graduation-cap"></i> Academics</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @if($user->hasPermission('manage-calendar'))<a href="{{ route('admin.livewire.resource','calendar') }}"><i class="fas fa-calendar-alt"></i><span>Calendar</span></a>@endif
                @if($user->hasPermission('manage-teachers'))<a href="{{ route('admin.livewire.resource','teachers') }}"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>@endif
                @if($user->hasPermission('manage-sections'))<a href="{{ route('admin.livewire.resource','sections') }}"><i class="fas fa-layer-group"></i><span>Sections</span></a>@endif
                @if($user->hasPermission('manage-subjects'))<a href="{{ route('admin.livewire.resource','subjects') }}"><i class="fas fa-book"></i><span>Subjects</span></a>@endif
                @if($user->hasPermission('manage-classes'))<a href="{{ route('admin.livewire.resource','classes') }}"><i class="fas fa-school"></i><span>Classes</span></a>@endif
                </div></details>
            @endif

            @if($user->hasPermission('manage-grading-scales') || $user->hasPermission('manage-remark-scales') || $user->hasPermission('manage-comments'))
                <div class="portal-nav-title">Assessment settings</div>
                <details><summary><span><i class="fas fa-clipboard-check"></i> Assessment Settings</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @if($user->hasPermission('manage-grading-scales'))<a href="{{ route('admin.livewire.resource','grading-scales') }}"><i class="fas fa-star-half-alt"></i><span>Grading Scales</span></a>@endif
                @if($user->hasPermission('manage-remark-scales'))<a href="{{ route('admin.livewire.resource','remark-scales') }}"><i class="fas fa-comment-dots"></i><span>Remark Scales</span></a>@endif
                @if($user->hasPermission('manage-comments'))<a href="{{ route('admin.livewire.resource','comments') }}"><i class="fas fa-comments"></i><span>Comments</span></a>@endif
                </div></details>
            @endif

            @if($user->hasPermission('manage-access-control'))
                <div class="portal-nav-title">Security</div>
                <details><summary><span><i class="fas fa-shield-alt"></i> Access Control</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                    <a href="{{ route('configuration.role.index') }}"><i class="fas fa-users-cog"></i><span>Roles</span></a>
                    <a href="{{ route('configuration.permission.index') }}"><i class="fas fa-key"></i><span>Permissions</span></a>
                    <a href="{{ route('configuration.role.permissions') }}"><i class="fas fa-link"></i><span>Role Permissions</span></a>
                </div></details>
            @endif
        @endif

        @if($portalRoles->contains('head'))
            <div class="portal-nav-title">Academic oversight</div>
            @if($user->hasPermission('manage-teachers') || $user->hasPermission('manage-class-teacher-allocation') || $user->hasPermission('manage-class-subjects'))
                <details><summary><span><i class="fas fa-chalkboard-teacher"></i> Teachers</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                    @if($user->hasPermission('manage-teachers'))<a href="{{ route('head.teachers.index') }}"><i class="fas fa-list-ul"></i><span>List of Teachers</span></a>@endif
                    @if($user->hasPermission('manage-class-teacher-allocation'))<a href="{{ route('head.teachers.classes') }}"><i class="fas fa-user-tag"></i><span>Class Allocation</span></a>@endif
                    @if($user->hasPermission('manage-class-subjects'))<a href="{{ route('head.teachers.subjects') }}"><i class="fas fa-book-reader"></i><span>Subject Allocation</span></a>@endif
                </div></details>
            @endif
            <details><summary><span><i class="fas fa-school"></i> Classes & Students</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @if($user->hasPermission('manage-class-subjects'))<a href="{{ route('section.index') }}"><i class="fas fa-book-open"></i><span>Class Subjects</span></a>@endif
                @if($user->hasPermission('manage-students'))<a href="{{ route('admission.student.index') }}"><i class="fas fa-user-graduate"></i><span>Students</span></a>@endif
                @if($user->hasAnyPermission('manage-students', 'manage-admissions'))<a class="{{ request()->routeIs('admission.promotions') ? 'active' : '' }}" href="{{ route('admission.promotions') }}"><i class="fas fa-level-up-alt"></i><span>Promotions</span></a>@endif
            </div></details>
            @if($user->hasPermission('manage-result-uploads'))<a href="{{ route('exam.upload.report') }}"><i class="fas fa-cloud-upload-alt"></i><span>Result Uploads</span></a>@endif

            @unless($hasFinancePortal)
                <div class="portal-nav-title">Finance & resources</div>
                <details><summary><span><i class="fas fa-wallet"></i> Finance</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                    @if($user->hasPermission('manage-payments'))<a href="{{ route('finance.payments.report') }}"><i class="fas fa-credit-card"></i><span>Payments</span></a>@endif
                    @if($user->hasPermission('manage-payments'))<a href="{{ route('finance.advance-payments.monitor') }}"><i class="fas fa-binoculars"></i><span>Advance Payment Monitor</span></a>@endif
                    @if($user->hasPermission('manage-sales'))<a href="{{ route('finance.inventory.sales') }}"><i class="fas fa-shopping-cart"></i><span>Sales</span></a>@endif
                    @if($user->hasPermission('manage-rents'))<a href="{{ route('finance.inventory.rents') }}"><i class="fas fa-hand-holding"></i><span>Rents</span></a>@endif
                </div></details>
                @if($user->hasPermission('manage-inventory'))<a href="{{ route('finance.inventory.view') }}"><i class="fas fa-boxes"></i><span>Inventory</span></a>@endif
            @endunless
        @endif

        @if($portalRoles->contains('admission_officer') && $user->hasPermission('manage-admissions'))
            <div class="portal-nav-title">Admissions</div>
            <a class="{{ request()->routeIs('admission.applications') ? 'active' : '' }}" href="{{ route('admission.applications') }}"><i class="fas fa-file-signature"></i><span>Applications</span></a>
            <a class="{{ request()->routeIs('admission.approvals') ? 'active' : '' }}" href="{{ route('admission.approvals') }}"><i class="fas fa-user-check"></i><span>Admission</span></a>
            <a class="{{ request()->routeIs('admission.students') ? 'active' : '' }}" href="{{ route('admission.students') }}"><i class="fas fa-user-graduate"></i><span>Students</span></a>
            <a class="{{ request()->routeIs('admission.promotions') ? 'active' : '' }}" href="{{ route('admission.promotions') }}"><i class="fas fa-level-up-alt"></i><span>Promotions</span></a>
            <a class="{{ request()->routeIs('admission.guardians') ? 'active' : '' }}" href="{{ route('admission.guardians') }}"><i class="fas fa-people-roof"></i><span>Guardians</span></a>
        @endif

        @if($portalRoles->contains('exam_officer') && ($user->hasPermission('manage-examinations') || $user->hasPermission('manage-result-uploads')))
            <div class="portal-nav-title">Examinations</div>
            <a href="{{ route('exam.upload.report') }}"><i class="fas fa-chart-bar"></i><span>Upload Report</span></a>
            <a href="{{ route('exam.upload.class.report') }}"><i class="fas fa-school"></i><span>Class Report</span></a>
            <details><summary><span><i class="fas fa-file-alt"></i> Sections</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @foreach(App\Models\Section::orderBy('name')->get() as $section)
                    <a href="{{ route('exam.upload.index', [$section->id]) }}"><i class="fas fa-upload"></i><span>{{ $section->name }} Uploads</span></a>
                    <a href="{{ route('exam.upload.result.accessCode', [$section->id]) }}"><i class="fas fa-key"></i><span>{{ $section->name }} Codes</span></a>
                @endforeach
            </div></details>
        @endif

        @if($hasFinancePortal)
            <div class="portal-nav-title">Finance</div>
            @if($portalRoles->contains('finance_officer'))
                @if($user->hasPermission('manage-fees'))
                <a href="{{ route('finance.fee-settings') }}"><i class="fas fa-money-check-alt"></i><span>Fees Setting</span></a>
                @endif
                @if($user->hasPermission('manage-payments'))
                <details><summary><span><i class="fas fa-credit-card"></i> Payments</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                    @foreach(App\Models\Fee::orderBy('name')->get() as $fee)
                        <a href="{{ route('finance.payments.collect', $fee->id) }}"><i class="fas fa-receipt"></i><span>{{ $fee->name }}</span></a>
                    @endforeach
                </div></details>
                <details><summary><span><i class="fas fa-forward"></i> Advance Payments</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                    @foreach(App\Models\Fee::orderBy('name')->get() as $fee)
                        <a href="{{ route('finance.advance-payments.collect', $fee->id) }}"><i class="fas fa-hourglass-half"></i><span>{{ $fee->name }}</span></a>
                    @endforeach
                </div></details>
                @endif
            @endif
            @if($user->hasPermission('manage-payments'))
            <details><summary><span><i class="fas fa-chart-line"></i> Payment Reports</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                <a href="{{ route('finance.payments.report') }}"><i class="fas fa-chart-pie"></i><span>Payments</span></a>
                <a href="{{ route('finance.payments.unpaid') }}"><i class="fas fa-exclamation-circle"></i><span>Unpaid Fees</span></a>
                @if($portalRoles->contains('head') || $portalRoles->contains('patron'))<a href="{{ route('finance.advance-payments.monitor') }}"><i class="fas fa-binoculars"></i><span>Advance Payment Monitor</span></a>@endif
            </div></details>
            @endif
            @if($portalRoles->contains('finance_officer') && $user->hasPermission('manage-payments'))<a href="{{ route('finance.activity-report') }}"><i class="fas fa-clipboard-list"></i><span>Activity Report</span></a>@endif
            @if($user->hasPermission('manage-inventory'))
            <details><summary><span><i class="fas fa-boxes"></i> Inventory</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                <a href="{{ route('finance.inventory.view') }}"><i class="fas fa-box"></i><span>Items</span></a>
                <a href="{{ route('finance.inventory.stock') }}"><i class="fas fa-box-open"></i><span>Stock</span></a>
                <a href="{{ route('finance.inventory.categories') }}"><i class="fas fa-tags"></i><span>Categories</span></a>
                <a href="{{ route('finance.inventory.sales') }}"><i class="fas fa-shopping-cart"></i><span>Sales</span></a>
                <a href="{{ route('finance.inventory.rents') }}"><i class="fas fa-hand-holding"></i><span>Rents</span></a>
                <a href="{{ route('finance.inventory.reconcile') }}"><i class="fas fa-balance-scale"></i><span>Stock Reconciliation</span></a>
            </div></details>
            @endif
            @if($portalRoles->contains('patron') && $user->hasPermission('view-school-analytics'))<details><summary><span><i class="fas fa-chart-pie"></i> Statistics</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu"><a href="{{ route('patron.statistics.students') }}"><i class="fas fa-user-graduate"></i><span>Students</span></a><a href="{{ route('patron.statistics.teachers') }}"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a></div></details>@endif
        @endif

        @if($portalRoles->contains('teacher') && $user->teacher)
            <div class="portal-nav-title">Teaching</div>
            <details><summary><span><i class="fas fa-book"></i> My Subjects</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @foreach(App\Models\SectionClassSubjectTeacher::where('teacher_id', $user->teacher->id)->with('sectionClassSubject.subject')->get() as $subject)
                    @if($subject->sectionClassSubject && $subject->sectionClassSubject->status === 'Active')<a href="{{ route('teacher.subject.index', $subject->id) }}"><i class="fas fa-book-open"></i><span>{{ $subject->sectionClassSubject->subject->name }}</span></a>@endif
                @endforeach
            </div></details>
            <details><summary><span><i class="fas fa-chalkboard"></i> My Classes</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @foreach(App\Models\SectionClassTeacher::where('teacher_id', $user->teacher->id)->with('sectionClass')->get() as $classTeacher)<a href="{{ route('teacher.class.index', [$classTeacher->id]) }}"><i class="fas fa-users"></i><span>{{ $classTeacher->sectionClass->name }}</span></a>@endforeach
            </div></details>
        @endif

        <div class="portal-nav-title">Account</div>
        <a class="{{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}"><i class="fas fa-user-cog"></i><span>My Profile</span></a>
        <a class="{{ request()->routeIs('password.*') ? 'active' : '' }}" href="{{ route('password.update.form') }}"><i class="fas fa-key"></i><span>Change Password</span></a>
    </nav>
    <div class="portal-account"><span class="portal-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><div><strong>{{ $user->name }}</strong><small>{{ $user->email }}</small></div><a href="#" title="Logout" onclick="event.preventDefault();document.getElementById('logout-form').submit()"><i class="fas fa-sign-out-alt"></i></a><form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form></div>
</aside>
