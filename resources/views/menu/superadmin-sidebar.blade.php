<div class="sa-mobile-bar">
    <button type="button" class="sa-menu-button" aria-label="Open navigation" onclick="document.body.classList.toggle('sa-sidebar-open')"><i class="fas fa-bars"></i></button>
    <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.jpg') }}" alt="FAYIS"><strong>FAYIS</strong></a>
</div>
<button class="sa-sidebar-overlay" aria-label="Close navigation" onclick="document.body.classList.remove('sa-sidebar-open')"></button>
<aside class="sa-sidebar" aria-label="Super administrator navigation">
    <a class="sa-brand" href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.jpg') }}" alt="FAYIS logo"><span><strong>FAYIS</strong><small>Super Admin</small></span></a>
    <nav>
        <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a class="{{ request()->routeIs('configuration.users.*') ? 'active' : '' }}" href="{{ route('configuration.users.index') }}"><i class="fas fa-user-cog"></i><span>User Management</span></a>
        <div class="sa-nav-title">Access control</div>
        <details>
            <summary><span><i class="fas fa-shield-alt"></i> Access Control</span><i class="fas fa-chevron-down sa-chevron"></i></summary>
            <div class="sa-submenu">
                <a class="{{ request()->routeIs('configuration.role.index') ? 'active' : '' }}" href="{{ route('configuration.role.index') }}"><i class="fas fa-users-cog"></i><span>Roles</span></a>
                <a class="{{ request()->routeIs('configuration.permission.index') ? 'active' : '' }}" href="{{ route('configuration.permission.index') }}"><i class="fas fa-key"></i><span>Permissions</span></a>
                <a class="{{ request()->routeIs('configuration.role.permissions') ? 'active' : '' }}" href="{{ route('configuration.role.permissions') }}"><i class="fas fa-link"></i><span>Role Permissions</span></a>
                <a class="{{ request()->routeIs('configuration.permission.users') ? 'active' : '' }}" href="{{ route('configuration.permission.users') }}"><i class="fas fa-user-lock"></i><span>User Roles</span></a>
            </div>
        </details>
    </nav>
    <div class="sa-account">
        <div><strong>{{ Auth::user()->name }}</strong><small>{{ Auth::user()->email }}</small></div>
        <a href="#" title="Logout" onclick="event.preventDefault();document.getElementById('logout-form').submit()"><i class="fas fa-sign-out-alt"></i></a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
    </div>
</aside>
