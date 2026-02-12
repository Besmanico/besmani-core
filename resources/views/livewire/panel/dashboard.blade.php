<div>


    
 
    <main class="panel-main">


        @livewire('panel.header') 

       {{-- new item dashboard --}}
       <div class="new-item-dashboard">
           <a href="{{ config('app.url') }}" class="dash-quick-link dash-home">
               <span class="icon-wrap home"><i class="fa fa-home"></i></span>
               <span class="label">Home</span>
           </a>
           <a href="https://beauty.besmani.com/" target="_blank" class="dash-quick-link dash-beauty">
               <span class="icon-wrap beauty"><i class="fa fa-magic"></i></span>
               <span class="label">Beauty</span>
           </a>
           <a href="{{ config('app.url') }}" class="dash-quick-link dash-marketplace">
               <span class="icon-wrap marketplace"><i class="fa fa-shopping-bag"></i></span>
               <span class="label">Marketplace</span>
           </a>
           <a href="{{ config('app.url') }}services" class="dash-quick-link dash-services">
               <span class="icon-wrap services"><i class="fa fa-cogs"></i></span>
               <span class="label">Services</span>
           </a> 
            <a href="#" class="dash-quick-link dash-ads">
                <span class="icon-wrap ads"><i class="fa fa-bullhorn"></i></span>
                <span class="label">Advertising</span>
            </a>
            <a href="#" class="dash-quick-link dash-messages">
                <span class="icon-wrap messages"><i class="fa fa-comments"></i></span>
                <span class="label">Messages</span>
            </a>
            {{-- <a href="#" class="dash-quick-link dash-logout">
                <span class="icon-wrap logout"><i class="fa fa-sign-out"></i></span>
                <span class="label">Log Out</span>
            </a> --}}
       </div>  

       {{-- new item dashboard end--}}

       <hr style="border: 1px solid #ccc; margin: 10px 0;">
        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-icon primary">
                    <i class="fa fa-tasks"></i>
                </div>
                <div>
                    <p class="stat-label">Active Projects</p>
                    <h2 class="stat-value">4</h2>
                </div>
            </article>
            <article class="stat-card">
                <div class="stat-icon success">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div>
                    <p class="stat-label">Completed Tasks</p>
                    <h2 class="stat-value">18</h2>
                </div>
            </article>
            <article class="stat-card">
                <div class="stat-icon warning">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div>
                    <p class="stat-label">Upcoming Deadlines</p>
                    <h2 class="stat-value">3</h2>
                </div>
            </article>
        </section>

        <section class="content-grid">
            <article class="panel-card">
                <div class="card-header">
                    <h3>Recent Activity</h3>
                    <a href="#" class="view-all">View all</a>
                </div>
                <ul class="activity-list">
                    <li>
                        <div class="bullet primary"></div>
                        <div>
                            <p class="activity-title">New project proposal submitted</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </li>
                    <li>
                        <div class="bullet success"></div>
                        <div>
                            <p class="activity-title">Task “Marketing assets” completed</p>
                            <span class="activity-time">Yesterday</span>
                        </div>
                    </li>
                    <li>
                        <div class="bullet warning"></div>
                        <div>
                            <p class="activity-title">Meeting scheduled with design team</p>
                            <span class="activity-time">Tomorrow 10:00 AM</span>
                        </div>
                    </li>
                </ul>
            </article>

            <article class="panel-card announcements">
                <div class="card-header">
                    <h3>Announcements</h3>
                </div>
                <div class="announcement">
                    <h4>Platform Update</h4>
                    <p>We’ve introduced a new analytics dashboard available from next week.</p>
                    <span class="announcement-date">Nov 9, 2025</span>
                </div>
                <div class="announcement">
                    <h4>Community Event</h4>
                    <p>Join our live Q&A session with the product team on Friday.</p>
                    <span class="announcement-date">Nov 12, 2025</span>
                </div>
            </article>
        </section>


    </main>
</div>
