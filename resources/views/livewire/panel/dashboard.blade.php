<div data-initial-tab="{{ $isPersonalUser ?? false ? 'personal' : 'business' }}">


    <main class="panel-main">


        @livewire('panel.header')

        @php
            $isPersonalUser = $isPersonalUser ?? false;
        @endphp
        {{-- tab --}}
        <div class="panel-tabs-wrap">
            <div class="panel-tabs panel-tabs-in-header" id="activeItemsTabs">
                <button type="button" class="panel-tab {{ $isPersonalUser ? 'panel-tab-add-business' : 'active' }}"
                    data-tab="business" data-add-business-modal="{{ $isPersonalUser ? '1' : '0' }}"
                    aria-selected="{{ $isPersonalUser ? 'false' : 'true' }}">
                    @if ($isPersonalUser)
                        <i class="fa fa-plus"></i>
                    @else
                        <i class="fa fa-briefcase"></i>
                    @endif
                    <span>{{ $isPersonalUser ? 'Add Business' : 'Business' }}</span>
                </button>
                <button type="button" class="panel-tab {{ $isPersonalUser ? 'active' : '' }}" data-tab="personal"
                    aria-selected="{{ $isPersonalUser ? 'true' : 'false' }}">
                    <i class="fa fa-user"></i>
                    <span>Personal</span>
                </button>  
            </div>
        </div>
        {{-- end tab --}}

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
            <a href="https://beauty.besmani.com/category" target="_blank" class="dash-quick-link dash-marketplace">
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
            <a href="{{ route('panel.referral') }}" class="dash-quick-link dash-referrals">
                <span class="icon-wrap referrals">
                    <i class="fa f fa-exchange"></i>
                </span>
                <span class="label">Referrals</span>
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

        {{-- new item dashboard end --}}

        <hr style="border: 1px solid #ccc; margin: 10px 0;">
        <section class="stats-grid" id="contentTabs">
            <article class="stat-card stat-card-deadlines {{ $isPersonalUser ? '' : 'active' }}" data-content="pending"
                role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-clock-o"></i></span>
                <p class="stat-label">Pending</p>
            </article>
            <article class="stat-card stat-card-active" data-content="active" role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-tasks"></i></span>
                <p class="stat-label">Active </p>
            </article>
            <article class="stat-card stat-card-completed" data-content="completed" role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-check-circle"></i></span>
                <p class="stat-label">Completed </p>
            </article>
            <article class="stat-card stat-card-quick {{ $isPersonalUser ? 'active' : '' }}" data-content="quick"
                role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-bolt"></i></span>
                <p class="stat-label">Quick Access
                </p>
            </article>
            {{-- <article class="stat-card stat-card-activity" data-content="activity" role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-history"></i></span>
                <p class="stat-label">Recent Activity</p>
            </article> --}}
            <article class="stat-card stat-card-insights" data-content="insights" role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-lightbulb-o"></i></span>
                <p class="stat-label">Insights</p>
            </article>
            <article class="stat-card stat-card-activity" data-content="activity" role="button" tabindex="0">
                <span class="stat-card-icon"><i class="fa fa-building"></i></span>
                <p class="stat-label"> My Business </p>
            </article>

        </section>

        <section class="content-grid">
            <div class="content-grid-switcher">
                {{-- Pending Items (default for business) --}}
                <article class="content-pane {{ $isPersonalUser ? '' : 'active' }}" id="content-pending">
                    <article class="panel-card">
                        <div class="card-header">
                            <h3>Pending Items
                                <p class="card-header-subtitle pending-business-subtitle">Awaiting Your Response</p>
                                <p class="card-header-subtitle pending-personal-subtitle">Awaiting Confirmation </p>
                            </h3>


                            {{-- <b style="font-size: 15px; color: #444;   margin: 0 auto;">Next up --}}

                            <p class="card-header-subtitle mt-12 next-up-business-subtitle">Needs Attention </p>
                            <p class="card-header-subtitle mt-12 next-up-personal-subtitle"> In Review </p>


                            {{-- </b>  --}}

                            <a href="#" class="view-all">View all</a>
                        </div>
                        <hr style="margin-top: 8px; border-top: 1px solid #e5a7a7;">

                        {{-- Pending: Business --}}
                        <div class="panel-tab-pane {{ $isPersonalUser ? '' : 'active' }}" id="tab-pending-business">
                            <div class="activity-two-cols">
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        @foreach (BeautyPendingItems() as $item)
                                            <li>
                                                @if (!empty($item->linkToBeautyClinic))
                                                    <a href="{{ $item->linkToBeautyClinic }}" target="_blank"
                                                        rel="noopener noreferrer" class="activity-list-link">
                                                @endif
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">{{ $item->title }} <b
                                                            class="color-green">{{ $item->reserveCountBC ?? ($item->reserveCountMenAcademy ?? ($item->reserveCountWomanAcademy ?? ($item->reserveCountManSalon ?? ($item->reserveCountWomanSalon ?? 0)))) }}</b>
                                                    </p>
                                                </div>
                                                @if (!empty($item->linkToBeautyClinic))
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                        @if (recentActivityPendingItemCount() > 0)
                                            <li>
                                                <a href="{{ config('app.url') }}panel/invoice"
                                                    class="activity-list-link">
                                                    <div class="bullet warning"></div>
                                                    <div>
                                                        <p class="activity-title">Website Design <b
                                                                class="color-orange">{{ recentActivityPendingItemCount() }}</b>
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>

                                </div>
                                {{-- next up --}}
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        @if (BeautyReservePendingItemsPersonnel()['clinicReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonnel()['clinicReserveBusiness']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ BeautyReservePendingItemsPersonnel()['clinicReserveBusiness']->day }}
                                                        {{ BeautyReservePendingItemsPersonnel()['clinicReserveBusiness']->day_date }}
                                                        {{ BeautyReservePendingItemsPersonnel()['clinicReserveBusiness']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonnel()['womanSalonReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonnel()['womanSalonReserveBusiness']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ BeautyReservePendingItemsPersonnel()['womanSalonReserveBusiness']->day }}
                                                        {{ BeautyReservePendingItemsPersonnel()['womanSalonReserveBusiness']->day_date }}
                                                        {{ BeautyReservePendingItemsPersonnel()['womanSalonReserveBusiness']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonnel()['manSalonReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonnel()['manSalonReserveBusiness']->serviceName }}
                                                    </p>
                                                    @if (!empty(BeautyReservePendingItemsPersonnel()['manSalonReserveBusiness']->day_date))
                                                        <span
                                                            class="activity-time">{{ BeautyReservePendingItemsPersonnel()['manSalonReserveBusiness']->day ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonnel()['manSalonReserveBusiness']->day_date }}
                                                            {{ BeautyReservePendingItemsPersonnel()['manSalonReserveBusiness']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonnel()['womanAcademyReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonnel()['womanAcademyReserveBusiness']->serviceName }}
                                                    </p>
                                                    @if (!empty(BeautyReservePendingItemsPersonnel()['womanAcademyReserveBusiness']->day_date))
                                                        <span
                                                            class="activity-time">{{ BeautyReservePendingItemsPersonnel()['womanAcademyReserveBusiness']->day ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonnel()['womanAcademyReserveBusiness']->day_date }}
                                                            {{ BeautyReservePendingItemsPersonnel()['womanAcademyReserveBusiness']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonnel()['manAcademyReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonnel()['manAcademyReserveBusiness']->serviceName }}
                                                    </p>
                                                    @if (!empty(BeautyReservePendingItemsPersonnel()['manAcademyReserveBusiness']->day_date))
                                                        <span
                                                            class="activity-time">{{ BeautyReservePendingItemsPersonnel()['manAcademyReserveBusiness']->day ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonnel()['manAcademyReserveBusiness']->day_date }}
                                                            {{ BeautyReservePendingItemsPersonnel()['manAcademyReserveBusiness']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Pending: Personal --}}
                        <div class="panel-tab-pane {{ $isPersonalUser ? 'active' : '' }}" id="tab-pending-personal">
                            <div class="activity-two-cols">
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        <li>
                                            <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()?->id ?? '' }}/?pageTo=reserve"
                                                target="_blank" class="activity-list-link">
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">Reserves <b
                                                            class="color-green">{{ BeautyReservePendingPersonalItems() }}</b>
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()?->id ?? '' }}/?pageTo=course"
                                                target="_blank" class="activity-list-link">
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">Courses <b
                                                            class="color-green">{{ BeautyCoursesPersonalItems() }}</b>
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        @if (BeautyReservePendingItemsPersonal()['clinicReserve'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonal()['clinicReserve']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ BeautyReservePendingItemsPersonal()['clinicReserve']->day }}
                                                        {{ BeautyReservePendingItemsPersonal()['clinicReserve']->day_date }}
                                                        {{ BeautyReservePendingItemsPersonal()['clinicReserve']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonal()['womanSalonReserve'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonal()['womanSalonReserve']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ BeautyReservePendingItemsPersonal()['womanSalonReserve']->day }}
                                                        {{ BeautyReservePendingItemsPersonal()['womanSalonReserve']->day_date }}
                                                        {{ BeautyReservePendingItemsPersonal()['womanSalonReserve']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonal()['manSalonReserve'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonal()['manSalonReserve']->serviceName }}
                                                    </p>
                                                    @if (!empty(BeautyReservePendingItemsPersonal()['manSalonReserve']->day_date))
                                                        <span
                                                            class="activity-time">{{ BeautyReservePendingItemsPersonal()['manSalonReserve']->day ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonal()['manSalonReserve']->day_date }}
                                                            {{ BeautyReservePendingItemsPersonal()['manSalonReserve']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonal()['womanAcademyReserve'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonal()['womanAcademyReserve']->serviceName }}
                                                    </p>
                                                    @if (!empty(BeautyReservePendingItemsPersonal()['womanAcademyReserve']->day_date ?? null))
                                                        <span
                                                            class="activity-time">{{ BeautyReservePendingItemsPersonal()['womanAcademyReserve']->day ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonal()['womanAcademyReserve']->day_date ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonal()['womanAcademyReserve']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                        @if (BeautyReservePendingItemsPersonal()['manAcademyReserve'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ BeautyReservePendingItemsPersonal()['manAcademyReserve']->serviceName }}
                                                    </p>
                                                    @if (!empty(BeautyReservePendingItemsPersonal()['manAcademyReserve']->day_date ?? null))
                                                        <span
                                                            class="activity-time">{{ BeautyReservePendingItemsPersonal()['manAcademyReserve']->day ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonal()['manAcademyReserve']->day_date ?? '' }}
                                                            {{ BeautyReservePendingItemsPersonal()['manAcademyReserve']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </article>
                </article>

                {{-- Active Items --}}
                <article class="content-pane" id="content-active">
                    <article class="panel-card">
                        <div class="card-header">
                            <h3>Active Items

                                <p class="card-header-subtitle active-items-business-subtitle">In Progress
                                </p>
                                <p class="card-header-subtitle active-items-personal-subtitle">Ongoing
                                </p>

                            </h3>
                            <b style="color: #8c929d;
  font-size: 14px;
  font-weight: 500;
  margin-top: 18px;">Next
                                up</b>

                            <a href="#" class="view-all">View all</a>
                        </div>

                        <div class="panel-card-subheader"
                            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">


                        </div>
                        <hr style="margin-top: 8px; border-top: 1px solid #e5a7a7;">

                        {{-- Tab Content: Business --}}
                        <div class="panel-tab-pane {{ $isPersonalUser ? '' : 'active' }}" id="tab-business">
                            <div class="activity-two-cols">
                                <div class="activity-col">
                                    {{-- <h4 class="activity-col-title">Active Items</h4> --}}
                                    <ul class="activity-list">
                                        @foreach (BeautyActiveItems() as $item)
                                            <li>
                                                @if (!empty($item->linkToBeautyClinic))
                                                    <a href="{{ $item->linkToBeautyClinic }}" target="_blank"
                                                        rel="noopener noreferrer" class="activity-list-link">
                                                @endif
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">{{ $item->title }} <b
                                                            class="color-green">{{ $item->reserveCountBC ?? ($item->reserveCountMenAcademy ?? ($item->reserveCountWomanAcademy ?? ($item->reserveCountManSalon ?? ($item->reserveCountWomanSalon ?? 0)))) }}</b>
                                                    </p>
                                                </div>
                                                @if (!empty($item->linkToBeautyClinic))
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                        @if (recentActivityStartingItemCount() > 0)
                                            <li>
                                                <a href="{{ config('app.url') }}panel/invoice"
                                                    class="activity-list-link">
                                                    <div class="bullet warning"></div>
                                                    <div>
                                                        <p class="activity-title">Website Design <b
                                                                class="color-orange">{{ recentActivityStartingItemCount() }}</b>
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        @if (AcrossAllModulesServicesPersonnel()['clinicReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServicesPersonnel()['clinicReserveBusiness']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ AcrossAllModulesServicesPersonnel()['clinicReserveBusiness']->day }}
                                                        {{ AcrossAllModulesServicesPersonnel()['clinicReserveBusiness']->day_date }}
                                                        {{ AcrossAllModulesServicesPersonnel()['clinicReserveBusiness']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (AcrossAllModulesServicesPersonnel()['womanSalonReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServicesPersonnel()['womanSalonReserveBusiness']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ AcrossAllModulesServicesPersonnel()['womanSalonReserveBusiness']->day }}
                                                        {{ AcrossAllModulesServicesPersonnel()['womanSalonReserveBusiness']->day_date }}
                                                        {{ AcrossAllModulesServicesPersonnel()['womanSalonReserveBusiness']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (AcrossAllModulesServicesPersonnel()['manSalonReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServicesPersonnel()['manSalonReserveBusiness']->serviceName }}
                                                    </p>
                                                    @if (!empty(AcrossAllModulesServicesPersonnel()['manSalonReserveBusiness']->day_date))
                                                        <span
                                                            class="activity-time">{{ AcrossAllModulesServicesPersonnel()['manSalonReserveBusiness']->day ?? '' }}
                                                            {{ AcrossAllModulesServicesPersonnel()['manSalonReserveBusiness']->day_date }}
                                                            {{ AcrossAllModulesServicesPersonnel()['manSalonReserveBusiness']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                        @if (AcrossAllModulesServicesPersonnel()['womanAcademyReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServicesPersonnel()['womanAcademyReserveBusiness']->serviceName }}
                                                    </p>
                                                    @if (!empty(AcrossAllModulesServicesPersonnel()['womanAcademyReserveBusiness']->day_date))
                                                        <span
                                                            class="activity-time">{{ AcrossAllModulesServicesPersonnel()['womanAcademyReserveBusiness']->day ?? '' }}
                                                            {{ AcrossAllModulesServicesPersonnel()['womanAcademyReserveBusiness']->day_date }}
                                                            {{ AcrossAllModulesServicesPersonnel()['womanAcademyReserveBusiness']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                        @if (AcrossAllModulesServicesPersonnel()['manAcademyReserveBusiness'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServicesPersonnel()['manAcademyReserveBusiness']->serviceName }}
                                                    </p>
                                                    @if (!empty(AcrossAllModulesServicesPersonnel()['manAcademyReserveBusiness']->day_date))
                                                        <span
                                                            class="activity-time">{{ AcrossAllModulesServicesPersonnel()['manAcademyReserveBusiness']->day ?? '' }}
                                                            {{ AcrossAllModulesServicesPersonnel()['manAcademyReserveBusiness']->day_date }}
                                                            {{ AcrossAllModulesServicesPersonnel()['manAcademyReserveBusiness']->hour ?? '' }}</span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Tab Content: Personal --}}
                        <div class="panel-tab-pane {{ $isPersonalUser ? 'active' : '' }}" id="tab-personal">
                            <div class="activity-two-cols">
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        {{-- @if (AcrossAllModulesServices()['womanAcademyReserve'] ?? null)
                                    <li>
                                        <div class="bullet success"></div>
                                        <div>
                                            <p class="activity-title">{{ AcrossAllModulesServices()['womanAcademyReserve']->serviceName }}</p>
                                        </div>
                                    </li> 
                                @endif --}}
                                        @if (AcrossAllModulesServices()['manAcademyReserve'] ?? null)
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServices()['manAcademyReserve']->serviceName }}
                                                    </p>
                                                </div>
                                            </li>
                                        @endif
                                        <li>
                                            <div class="bullet success"></div>
                                            <div>
                                                <a href="{{ env('BEAUTY_URL') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=reserve"
                                                    target="_blank">

                                                    <p class="activity-title">Reserves <b
                                                            class="color-green">{{ BeautyReservePersonalItems() }}</b>
                                                    </p>
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="bullet success"></div>
                                            <div>
                                                <a href="{{ env('BEAUTY_URL') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=course"
                                                    target="_blank">

                                                    <p class="activity-title">Courses <b class="color-green">
                                                            {{ BeautyCoursesPersonalItems() }}

                                                        </b>
                                                    </p>
                                                </a>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        @if (AcrossAllModulesServices()['clinicReserve'])
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServices()['clinicReserve']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ AcrossAllModulesServices()['clinicReserve']->day }}
                                                        {{ AcrossAllModulesServices()['clinicReserve']->day_date }}
                                                        {{ AcrossAllModulesServices()['clinicReserve']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (AcrossAllModulesServices()['womanSalonReserve'])
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServices()['womanSalonReserve']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ AcrossAllModulesServices()['womanSalonReserve']->day }}
                                                        {{ AcrossAllModulesServices()['womanSalonReserve']->day_date }}
                                                        {{ AcrossAllModulesServices()['womanSalonReserve']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        @if (AcrossAllModulesServices()['manSalonReserve'])
                                            <li>
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">
                                                        {{ AcrossAllModulesServices()['manSalonReserve']->serviceName }}
                                                    </p>
                                                    <span
                                                        class="activity-time">{{ AcrossAllModulesServices()['manSalonReserve']->day }}
                                                        {{ AcrossAllModulesServices()['manSalonReserve']->day_date }}
                                                        {{ AcrossAllModulesServices()['manSalonReserve']->hour }}</span>
                                                </div>
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>

                    </article>
                </article>

                {{-- Completed Tasks --}}
                <article class="content-pane" id="content-completed">
                    <article class="panel-card">
                        <div class="card-header">
                            <h3>Completed Tasks

                                <p class="card-header-subtitle completed-business-subtitle">Delivered </p>
                                <p class="card-header-subtitle completed-personal-subtitle">Finished </p>
                            </h3>
                            <a href="#" class="view-all">View all</a>
                        </div>
                        <hr style="margin-top: 8px; border-top: 1px solid #e5a7a7;">
                        <ul class="activity-list">
                            @foreach (BeautyDoneItems() as $item)
                                <li>
                                    @if (!empty($item->linkToBeautyClinic))
                                        <a href="{{ $item->linkToBeautyClinic }}" target="_blank"
                                            rel="noopener noreferrer" class="activity-list-link">
                                    @endif
                                    <div class="bullet success"></div>
                                    <div>
                                        <p class="activity-title">{{ $item->title }} <b
                                                class="color-green">{{ $item->reserveCountBC ?? ($item->reserveCountMenAcademy ?? ($item->reserveCountWomanAcademy ?? ($item->reserveCountManSalon ?? ($item->reserveCountWomanSalon ?? 0)))) }}</b>
                                        </p>
                                    </div>
                                    @if (!empty($item->linkToBeautyClinic))
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </article>
                </article>

                {{-- Quick Actions (default for personal / service_pr==0) --}}
                <article class="content-pane {{ $isPersonalUser ? 'active' : '' }}" id="content-quick">
                    <article class="panel-card">
                        <div class="card-header">
                            <h3> Action Center
                                <p class="card-header-subtitle quick-access-subtitle">Shortcuts</p>
                            </h3>
                        </div>
                        <hr style="margin-top: 8px; border-top: 1px solid #e5a7a7;">

                        {{-- Quick: Business --}}
                        <div class="panel-tab-pane {{ $isPersonalUser ? '' : 'active' }}" id="tab-quick-business">
                            <div class="activity-two-cols">
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        <li>
                                            <a href="{{ env('BEAUTY_URL') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=addProduct"
                                                class="activity-list-link">
                                                <div class="color-green icon-add">+</div>
                                                <div>
                                                    <p class="activity-title">Add Product</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ env('BEAUTY_URL') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=addService"
                                                class="activity-list-link">
                                                <div class="color-green icon-add">+</div>
                                                <div>
                                                    <p class="activity-title">Add Service</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ env('BEAUTY_URL') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=addService"
                                                class="activity-list-link">
                                                <div class="color-green icon-add">+</div>
                                                <div>
                                                    <p class="activity-title">Create Course</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="activity-list-link">
                                                <div class="color-orange icon-add">+</div>
                                                <div>
                                                    <p class="activity-title">Create Promotion</p>
                                                </div>
                                            </a>
                                        </li>


                                    </ul>
                                </div>
                                {{-- <div class="activity-col">
                                    <ul class="activity-list">
                                        <li>
                                            <div class="bullet primary"></div>
                                            <div>
                                                <p class="activity-title">View Reports</p>
                                            </div>
                                        </li>
                                       
                                    </ul>
                                </div> --}}
                            </div>
                        </div>

                        {{-- Quick: Personal --}}
                        <div class="panel-tab-pane {{ $isPersonalUser ? 'active' : '' }}" id="tab-quick-personal">
                            <div class="activity-two-cols">
                                <div class="activity-col">
                                    <ul class="activity-list">
                                        <li>
                                            <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()?->id ?? '' }}/?pageTo=reserve"
                                                target="_blank" class="activity-list-link">
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title"> Book a Service</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()?->id ?? '' }}/?pageTo=course"
                                                target="_blank" class="activity-list-link">
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">Shop Products</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()?->id ?? '' }}/?pageTo=course"
                                                target="_blank" class="activity-list-link">
                                                <div class="bullet success"></div>
                                                <div>
                                                    <p class="activity-title">Explore Courses</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()?->id ?? '' }}/?pageTo=course"
                                                target="_blank" class="activity-list-link">
                                                <div class="bullet  orange "></div>
                                                <div>
                                                    <p class="activity-title"> View My Coupons</p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                {{-- <div class="activity-col">
                                    <ul class="activity-list">
                                        <li>
                                            <div class="bullet primary"></div>
                                            <div>
                                                <p class="activity-title">Quick personal actions will appear here</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                    </article>
                </article>

                {{-- Recent Activity --}}
                <article class="content-pane" id="content-activity">
                    <article class="panel-card">
                        <div class="card-header">
                            <h3> Active Businesses

                                <p class="card-header-subtitle active-business-subtitle">Overview </p>
                            </h3>
                            <b
                                style="color: #8c929d;
  font-size: 14px;
  font-weight: 500;
     margin-left: -55px;
  margin-top: 20px;">Add</b>
                            <a href="{{ url('/panel/business') }}" class="view-all">View all</a>
                        </div>
                        <hr style="margin-top: 8px; border-top: 1px solid #e5a7a7;">
                        <div class="activity-two-cols">
                            <div class="activity-col">
                                @php
                                    $businessList = BusinessList();
                                @endphp
                                @if ($businessList->isEmpty())
                                    <ul class="activity-list">
                                        <li>
                                            <div class="bullet success"></div>
                                            <div>
                                                <p class="activity-title">Connect your business to see recent activity
                                                    here.</p>
                                            </div>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="activity-list activity-list-business">
                                        @foreach ($businessList as $activity)
                                            @foreach ($activity->infoActivity ?? [] as $business)
                                                @php
                                                    $img = $business->image ?? '';
                                                    $beautyUrl = rtrim(
                                                        env('BEAUTY_URL', 'https://beauty.besmani.com'),
                                                        '/',
                                                    );
                                                    $activityImagesBase =
                                                        $beautyUrl . '/public/assets/images/activity_images/';
                                                    if ($img) {
                                                        $imgUrl = str_starts_with($img, 'http')
                                                            ? $img
                                                            : (str_contains($img, 'activity_images')
                                                                ? $beautyUrl . '/' . ltrim($img, '/')
                                                                : $activityImagesBase . basename($img));
                                                    } else {
                                                        $imgUrl = '';
                                                    }
                                                    $name = $business->name ?? ($business->title ?? '-');
                                                    $nameShort =
                                                        strlen($name) > 22 ? substr($name, 0, 22) . '...' : $name;
                                                @endphp
                                                <li>
                                                    <div class="business-list-activity-cell">
                                                        <div class="business-list-activity-img-wrap">
                                                            @if ($imgUrl)
                                                                <img src="{{ $imgUrl }}"
                                                                    alt="{{ $name }}"
                                                                    class="business-list-activity-img">
                                                            @else
                                                                <div
                                                                    class="business-list-activity-img business-list-activity-img-placeholder">
                                                                    <i class="fa fa-building"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="business-list-activity-text">
                                                            <span class="business-list-activity-title">
                                                                {{ $activity->title ?? ($activity->title_en ?? 'Activity') }}
                                                                :
                                                            </span>
                                                            <span
                                                                class="business-list-activity-name">{{ $nameShort }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="activity-col">
                                <ul class="activity-list">
                                    <li>
                                        <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=newBusiness"
                                            target="_blank" rel="noopener noreferrer" class="activity-list-link">
                                            <div class="color-green icon-add">+</div> 
                                            <div>
                                                <p class="activity-title">New Business</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ rtrim(env('BEAUTY_URL', 'https://beauty.besmani.com'), '/') }}/login/recentActivityBesmani/{{ Auth::guard('mainUsers')->user()->id }}/?pageTo=newBusiness"
                                            target="_blank" rel="noopener noreferrer" class="activity-list-link">
                                            <div class="color-green icon-add">+</div>
                                            <div>
                                                <p class="activity-title">New Branch</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </article>
                </article>

                {{-- Insights --}}
                <article class="content-pane" id="content-insights">
                    <article class="panel-card">
                        <div class="card-header">
                            <h3>Insights</h3>
                        </div>
                        <hr style="margin-top: 8px; border-top: 1px solid #e5a7a7;">
                        <ul class="activity-list">
                            <li>
                                <div class="bullet primary"></div>
                                <div>
                                    <p class="activity-title">Insights and analytics will appear here</p>
                                </div>
                            </li>
                        </ul>
                    </article>
                </article>
            </div>

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

        {{-- Add Business Type Modal (for service_pr==0) --}}
        <div id="add-business-type-modal" class="add-business-modal-overlay" style="display: none;"
            aria-hidden="true">
            <div class="add-business-modal" role="dialog" aria-labelledby="add-business-modal-title">
                <button type="button" class="add-business-modal-close" id="add-business-modal-close"
                    aria-label="Close">&times;</button>
                <p class="add-business-modal-step">Step 1 of 3</p>

                <h2 id="add-business-modal-title" class="add-business-modal-title">What type of business are you
                    adding?</h2>
                <p class="add-business-modal-subtitle">Choose the structure that fits your work.</p>
                <div class="add-business-modal-header-line"></div>

                <div class="add-business-modal-options">
                    <button type="button" class="add-business-option" data-type="service">
                        <span class="add-business-option-icon"><i class="fa fa-scissors"></i></span>
                        <span class="add-business-option-label">Service Business (Clinic / Salon / Studio)</span>
                    </button>
                    <button type="button" class="add-business-option" data-type="store">
                        <span class="add-business-option-icon"><i class="fa fa-shopping-bag"></i></span>
                        <span class="add-business-option-label">Store (Products / Retail)</span>
                    </button>
                    <button type="button" class="add-business-option" data-type="academy">
                        <span class="add-business-option-icon"><i class="fa fa-graduation-cap"></i></span>
                        <span class="add-business-option-label">Academy (Courses / Classes)</span>
                    </button>
                </div>
                <div class="add-business-modal-footer">
                    {{-- <button type="button" class="add-business-btn add-business-btn-continue" id="add-business-modal-continue">Continue</button> --}}
                    <button type="button" class="add-business-btn add-business-btn-cancel"
                        id="add-business-modal-cancel">Cancel</button>

                </div>
            </div>
        </div>

    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var root = document.querySelector('[data-initial-tab]');
        var initialTab = root ? root.getAttribute('data-initial-tab') : null;
        if (initialTab) {
            var tabKey = initialTab;
            var subBusiness = document.querySelector('.pending-business-subtitle');
            var subPersonal = document.querySelector('.pending-personal-subtitle');
            if (subBusiness && subPersonal) {
                subBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                subPersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
            }
            var nextBusiness = document.querySelector('.next-up-business-subtitle');
            var nextPersonal = document.querySelector('.next-up-personal-subtitle');
            if (nextBusiness && nextPersonal) {
                nextBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                nextPersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
            }
            var activeBusiness = document.querySelector('.active-items-business-subtitle');
            var activePersonal = document.querySelector('.active-items-personal-subtitle');
            if (activeBusiness && activePersonal) {
                activeBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                activePersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
            }
            var completedBusiness = document.querySelector('.completed-business-subtitle');
            var completedPersonal = document.querySelector('.completed-personal-subtitle');
            if (completedBusiness && completedPersonal) {
                completedBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                completedPersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
            }
        }

        var tabs = document.querySelectorAll('#activeItemsTabs .panel-tab');
        var panes = document.querySelectorAll('.panel-tab-pane');
        var addBusinessModal = document.getElementById('add-business-type-modal');
        var addBusinessModalClose = document.getElementById('add-business-modal-close');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                if (this.getAttribute('data-add-business-modal') === '1') {
                    if (addBusinessModal) {
                        addBusinessModal.style.display = 'flex';
                        addBusinessModal.setAttribute('aria-hidden', 'false');
                    }
                    return;
                }
                var tabKey = this.getAttribute('data-tab');
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                panes.forEach(function(p) {
                    p.classList.remove('active');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                var paneActive = document.getElementById('tab-' + tabKey);
                var panePending = document.getElementById('tab-pending-' + tabKey);
                var paneQuick = document.getElementById('tab-quick-' + tabKey);
                if (paneActive) paneActive.classList.add('active');
                if (panePending) panePending.classList.add('active');
                if (paneQuick) paneQuick.classList.add('active');
                var subBusiness = document.querySelector('.pending-business-subtitle');
                var subPersonal = document.querySelector('.pending-personal-subtitle');
                if (subBusiness && subPersonal) {
                    subBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                    subPersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
                }
                var nextBusiness = document.querySelector('.next-up-business-subtitle');
                var nextPersonal = document.querySelector('.next-up-personal-subtitle');
                if (nextBusiness && nextPersonal) {
                    nextBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                    nextPersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
                }
                var activeBusiness = document.querySelector('.active-items-business-subtitle');
                var activePersonal = document.querySelector('.active-items-personal-subtitle');
                if (activeBusiness && activePersonal) {
                    activeBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                    activePersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
                }
                var completedBusiness = document.querySelector('.completed-business-subtitle');
                var completedPersonal = document.querySelector('.completed-personal-subtitle');
                if (completedBusiness && completedPersonal) {
                    completedBusiness.style.display = tabKey === 'business' ? 'block' : 'none';
                    completedPersonal.style.display = tabKey === 'personal' ? 'block' : 'none';
                }
            });
        });

        var statCards = document.querySelectorAll('#contentTabs .stat-card');
        var contentPanes = document.querySelectorAll('.content-pane');
        statCards.forEach(function(card) {
            card.addEventListener('click', function() {
                var targetId = 'content-' + this.getAttribute('data-content');
                statCards.forEach(function(c) {
                    c.classList.remove('active');
                });
                contentPanes.forEach(function(p) {
                    p.classList.remove('active');
                });
                this.classList.add('active');
                var pane = document.getElementById(targetId);
                if (pane) pane.classList.add('active');
            });
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        if (addBusinessModal) {
            function closeAddBusinessModal() {
                addBusinessModal.style.display = 'none';
                addBusinessModal.setAttribute('aria-hidden', 'true');
            }
            if (addBusinessModalClose) {
                addBusinessModalClose.addEventListener('click', closeAddBusinessModal);
            }
            var addBusinessCancel = document.getElementById('add-business-modal-cancel');
            var addBusinessContinue = document.getElementById('add-business-modal-continue');
            if (addBusinessCancel) addBusinessCancel.addEventListener('click', closeAddBusinessModal);
            if (addBusinessContinue) {
                addBusinessContinue.addEventListener('click', function() {
                    closeAddBusinessModal();
                });
            }
            addBusinessModal.addEventListener('click', function(e) {
                if (e.target === addBusinessModal) closeAddBusinessModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && addBusinessModal.style.display === 'flex')
                    closeAddBusinessModal();
            });
            document.querySelectorAll('.add-business-option').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var type = this.getAttribute('data-type');
                    closeAddBusinessModal();
                });
            });
        }
    });
</script>
