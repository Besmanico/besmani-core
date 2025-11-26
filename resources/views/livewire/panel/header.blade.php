<div>
    <header class="panel-header">
        <div>
            {{-- show title based on current page --}}
            <h1>{{ $this->title }}</h1>
            
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
