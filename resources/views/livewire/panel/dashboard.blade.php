<div>
    

        <main class="panel-main">


            @livewire('panel.header')


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
