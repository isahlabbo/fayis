<div class="card border-0 shadow-sm" style="border-radius:18px">
    <div class="card-body text-center py-5">
        <i class="fas fa-user-circle text-success mb-3" style="font-size:3rem"></i>
        <h2 class="h4">Welcome, {{ Auth::user()->name }}</h2>
        <p class="text-muted mb-3">Your account does not currently have a dedicated dashboard.</p>
        <a href="{{ route('profile.show') }}" class="btn btn-outline-success"><i class="fas fa-user-cog mr-1"></i> View profile</a>
    </div>
</div>
