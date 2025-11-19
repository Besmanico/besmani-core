<div>
    <header class="panel-header">
        <div>
            <h1>Personal Panel</h1>
            <p class="subtitle">Track your activity, manage projects, and stay on top of the latest updates.</p>
        </div>
        <div class="user-summary">
            <div class="avatar">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <span class="user-name">{{ Auth::guard('mainUsers')->user()->fl_name ?? 'Guest' }}</span>
                <span class="user-role">Member</span>
            </div>
        </div>
    </header>
</div>
