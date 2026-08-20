@php
    $user = Auth::user();
    $user->loadMissing(['accessRoles.permissions', 'directPermissions']);
    $roleLabels = [
        'admin' => 'Administrator', 'head' => 'Head of School', 'admission_officer' => 'Admissions',
        'exam_officer' => 'Examinations', 'finance_officer' => 'Finance', 'patron' => 'Patron',
        'teacher' => 'Teacher', 'guardian' => 'Guardian', 'staff' => 'Staff',
    ];
    $roleLabel = $roleLabels[$user->role] ?? ucwords(str_replace('_', ' ', $user->role));
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

        @if($user->role === 'admin')
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
        @elseif($user->role === 'head')
            <div class="portal-nav-title">School management</div>
            <details><summary><span><i class="fas fa-university"></i> Sections</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @foreach(App\Models\Section::orderBy('name')->get() as $section)<a href="{{ route('section.classes', [$section->id]) }}"><i class="fas fa-school"></i><span>{{ $section->name }}</span></a>@endforeach
                <a href="{{ route('section.index') }}"><i class="fas fa-cog"></i><span>Manage Sections</span></a>
            </div></details>
            <details><summary><span><i class="fas fa-building"></i> Administration</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                <a href="{{ route('administration.user.index') }}"><i class="fas fa-user-tie"></i><span>Administrators</span></a>
                <a href="{{ route('administration.session.index') }}"><i class="fas fa-calendar"></i><span>Academic Sessions</span></a>
                <a href="{{ route('administration.teacher.index') }}"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
                <a href="{{ route('administration.staff.index') }}"><i class="fas fa-users"></i><span>Other Staff</span></a>
                <a href="{{ route('administration.card.index') }}"><i class="fas fa-id-card"></i><span>ID Card Requests</span></a>
            </div></details>
            <a href="{{ route('configuration.reportcard.index') }}"><i class="fas fa-sliders-h"></i><span>Report Configuration</span></a>
        @elseif($user->role === 'admission_officer')
            <div class="portal-nav-title">Admissions</div>
            <a class="{{ request()->routeIs('admission.student.*') ? 'active' : '' }}" href="{{ route('admission.student.index') }}"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        @elseif($user->role === 'exam_officer')
            <div class="portal-nav-title">Examinations</div>
            <a href="{{ route('exam.upload.report') }}"><i class="fas fa-chart-bar"></i><span>Upload Report</span></a>
            <a href="{{ route('exam.upload.class.report') }}"><i class="fas fa-school"></i><span>Class Report</span></a>
            <details><summary><span><i class="fas fa-file-alt"></i> Sections</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                @foreach(App\Models\Section::orderBy('name')->get() as $section)
                    <a href="{{ route('exam.upload.index', [$section->id]) }}"><i class="fas fa-upload"></i><span>{{ $section->name }} Uploads</span></a>
                    <a href="{{ route('exam.upload.result.accessCode', [$section->id]) }}"><i class="fas fa-key"></i><span>{{ $section->name }} Codes</span></a>
                @endforeach
            </div></details>
        @elseif(in_array($user->role, ['finance_officer', 'patron'], true))
            <div class="portal-nav-title">Finance</div>
            @if($user->role === 'finance_officer')
                <details><summary><span><i class="fas fa-coins"></i> Sections & Fees</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                    @foreach(App\Models\Section::orderBy('name')->get() as $section)
                        <a href="{{ route('finance.fees.classes', [$section->id]) }}"><i class="fas fa-file-invoice"></i><span>{{ $section->name }} Fees</span></a>
                        <a href="{{ route('finance.payments.classes', [$section->id, 'school']) }}"><i class="fas fa-credit-card"></i><span>{{ $section->name }} Payments</span></a>
                    @endforeach
                </div></details>
            @endif
            <details><summary><span><i class="fas fa-chart-line"></i> Payment Reports</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                <a href="{{ route('finance.payments.report') }}"><i class="fas fa-chart-pie"></i><span>Payments</span></a>
                <a href="{{ route('finance.payments.unpaid') }}"><i class="fas fa-exclamation-circle"></i><span>Unpaid Fees</span></a>
            </div></details>
            <details><summary><span><i class="fas fa-boxes"></i> Inventory</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu">
                <a href="{{ route('finance.inventory.view') }}"><i class="fas fa-box"></i><span>Items</span></a>
                <a href="{{ route('finance.inventory.stock') }}"><i class="fas fa-box-open"></i><span>Stock</span></a>
                <a href="{{ route('finance.inventory.categories') }}"><i class="fas fa-tags"></i><span>Categories</span></a>
                <a href="{{ route('finance.inventory.sales') }}"><i class="fas fa-shopping-cart"></i><span>Sales</span></a>
                <a href="{{ route('finance.inventory.rents') }}"><i class="fas fa-hand-holding"></i><span>Rents</span></a>
            </div></details>
            @if($user->role === 'patron')<details><summary><span><i class="fas fa-chart-pie"></i> Statistics</span><i class="fas fa-chevron-down portal-chevron"></i></summary><div class="portal-submenu"><a href="{{ route('patron.statistics.students') }}"><i class="fas fa-user-graduate"></i><span>Students</span></a><a href="{{ route('patron.statistics.teachers') }}"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a></div></details>@endif
        @elseif($user->role === 'teacher' && $user->teacher)
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
