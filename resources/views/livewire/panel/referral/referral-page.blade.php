<div>
    <link rel="stylesheet" href="{{ asset('assets-file/css/referrals.css') }}">

    @php
        $referralQuery = request()->only(['other', 'return_url']);
    @endphp

    <main class="panel-main referral-shell {{ $section === 'new' ? 'is-new-referral' : '' }}">
        <header class="ref-page-header">
            <div>
                {{-- <span class="ref-eyebrow">Besmani Referral Network</span> --}}
                <h1>{{ $isProvider ? 'Business Referrals' : 'My Referrals' }}</h1>
                <p>{{ $isProvider ? 'Manage referrals connected to you and your authorized businesses.' : 'Refer someone to a Besmani Provider and earn Besmani COIN after completion.' }}</p>
            </div>
            <nav class="ref-subnav {{ $isProvider ? '' : 'is-personal' }}" aria-label="Referral sections">
            <a href="{{ route('panel.referral', $referralQuery) }}" class="{{ $section === 'dashboard' ? 'active' : '' }}"><i class="fa fa-th-large"></i><span>Overview</span></a>
            <a href="{{ route('panel.referral.new', $referralQuery) }}" class="{{ $section === 'new' ? 'active' : '' }}"><i class="fa fa-exchange"></i><span> {{ $isProvider ? 'New Referral' : 'Refer Someone' }}</span></a>
        </nav>      
            <div class="ref-header-actions"> 
                      

                @if ($isProvider)
                    <button type="button" class="ref-btn ref-btn-secondary ref-use-bc-btn" wire:click="$set('showUseCoinsModal', true)">
                        <i class="fa fa-star"></i> Use BC
                    </button>
                @endif
                {{-- <a href="{{ route('panel.referral.new') }}" class="ref-btn ref-btn-primary">
                    <i class="fa fa-exchange"></i>
                    {{ $isProvider ? 'New Referral' : 'Refer Someone' }}
                </a> --}}
            </div> 
        </header>    
  
        @if ($showUseCoinsModal)
            <div class="ref-token-notice">
                <i class="fa fa-shield"></i>
                <p><strong>Besmani COIN (BC) is an internal, non-cash credit.</strong> BC is awarded only after a referral is Completed. It cannot be transferred, withdrawn, converted to cash, or used as cryptocurrency.</p>
            </div>
        @endif

     
        <div class="ref-page-content" wire:loading.class="is-loading" wire:target="createReferral,confirmAction,viewReferral">
            <div class="ref-loading-overlay" wire:loading.flex wire:target="createReferral,confirmAction,viewReferral">
                <x-referrals.ui-state type="loading" title="Please wait" message="Updating your referral information..." />
            </div>

            @if ($section === 'new') 
                <form wire:submit="createReferral" class="ref-form-layout">
                    <section class="ref-panel-card ref-form-card"> 
                          <div class="ref-section-heading">
                            <div><span class="ref-step">1</span><h2>Referral From</h2></div>
                            <p>Select the person or business sending this referral.</p>
                        </div>  
                        @if ($isProvider && $businesses->isNotEmpty())
                            <label class="ref-field">
                                <span>  who is sending this</span>
                                <select wire:model="referringAccount">
                                    <option value="">Select Personal or Business Account</option>
                                    <option value="personal">Personal Account — {{ $referringUserName ?: 'User' }}</option>
                                    @foreach ($businesses as $business)
                                        <option value="business:{{ $business->id }}">{{ $business->name ?? $business->title ?? 'Business' }}</option>
                                    @endforeach 
                                </select>
                                @error('referringAccount') <small class="ref-field-error">{{ $message }}</small> @enderror
                            </label> 
                        @else
                            <label class="ref-field">
                                <span>Who is sending this</span>
                                <input type="text" value="{{ $referringUserName ?: 'Personal Account' }}" readonly>
                            </label>
                        @endif

</section>
                    <section class="ref-panel-card ref-form-card"> 

                      
                        
         <div class="ref-section-heading">
                            <div><span class="ref-step">2</span><h2>Referral destination</h2></div>
                            <p>Choose a Provider or Business that will receive the customer.</p>
                        </div>   
                          

                        @php
                            $receiverTerm = trim($receiverSearch);
                            $receiverDigits = preg_replace('/\D+/', '', $receiverTerm);
                            $receiverLooksLikePhone = preg_match('/^[\d\s()+-]+$/', $receiverTerm) === 1;
                            $canSearchReceiver = mb_strlen($receiverTerm) >= 2
                                && (! $receiverLooksLikePhone || strlen($receiverDigits) >= 10);
                            $receiverResultCount = $receiverOptions->count();
                        @endphp
                        <div class="ref-combobox" x-data="{ active: -1 }" @click.outside="if ($wire.showDestinationDropdown) $wire.closeDestinationDropdown()">
                            <span class="ref-combobox-label">Search Provider or Business <b>*</b></span>
                            <div class="ref-combobox-control">
                                @if ($destination !== '' && $selectedDestinationName !== '')
                                    <div class="ref-combobox-selected">
                                        <i class="fa fa-building"></i>
                                        <span><strong>{{ $selectedDestinationName }}</strong><small>{{ $selectedDestinationDetails }}</small></span>
                                        <button type="button" wire:click="clearDestination" aria-label="Clear destination"><i class="fa fa-times"></i></button>
                                    </div>
                                @else     
                                    <div class="ref-input-icon ref-combobox-input">
                                        <i class="fa fa-search"></i>
                                        <input type="search"
                                        autocomplete="nope"
                                               wire:model.live.debounce.350ms="receiverSearch"
                                               placeholder="Search by Provider name, Business name, phone, email, or city"
                                               role="combobox"
                                               aria-autocomplete="list"
                                               aria-controls="ref-destination-results"
                                               aria-expanded="{{ $showDestinationDropdown && $canSearchReceiver ? 'true' : 'false' }}"
                                               wire:focus="openDestinationDropdown"
                                               wire:keyup.debounce.200ms="$refresh"
                                               @input="active = -1"
                                               @keydown.escape.prevent="$wire.closeDestinationDropdown(); active = -1"
                                               @keydown.arrow-down.prevent="$wire.openDestinationDropdown(); active = Math.min(active + 1, {{ max(0, $receiverResultCount - 1) }})"
                                               @keydown.arrow-up.prevent="active = Math.max(active - 1, 0)"
                                               @keydown.enter.prevent="if (active >= 0) $refs.results.querySelectorAll('[role=option]')[active]?.click()">
                                        <span class="ref-combobox-spinner" wire:loading wire:target="receiverSearch"><i class="fa fa-circle-o-notch fa-spin"></i></span>
                                    </div>

                                    @if ($canSearchReceiver && $showDestinationDropdown)
                                        <div id="ref-destination-results" class="ref-search-dropdown" role="listbox" x-ref="results">
                                            @forelse ($receiverOptions as $index => $option)
                                                <button type="button"
                                                        role="option"
                                                        :aria-selected="active === {{ $index }}"
                                                        :class="{ 'is-focused': active === {{ $index }} }"
                                                        class="ref-receiver-option"
                                                        wire:click="selectDestination('{{ $option['value'] }}')"
                                                        @mouseenter="active = {{ $index }}"
                                                        @click="active = -1">
                                                    <span class="ref-option-content"><strong>{{ $option['name'] }}</strong><small>{{ $option['details'] }}</small></span>
                                                    <i class="fa fa-check ref-option-check"></i>
                                                </button>
                                            @empty
                                                <div class="ref-inline-empty"> 
                                                    <strong>No Provider or Business found on Besmani.</strong>
                                                    <span>Ask them to register before sending a referral.</span>
                                                    <button type="button" class="ref-btn ref-btn-secondary " style="background-color: var(--ref-navy);color:white" wire:click="openInvite('provider')" wire:loading.attr="disabled" wire:target="openInvite('provider')"><span wire:loading.remove wire:target="openInvite('provider')">Invite Provider</span><span wire:loading wire:target="openInvite('provider')"><i class="fa fa-circle-o-notch fa-spin"></i> Creating invitation...</span></button>
                                                    @error('invitationRecipient') <small class="ref-field-error">{{ $message }}</small> @enderror
                                                </div>
                                            @endforelse
                                        </div>
                                    @endif
                                @endif
                            </div>
                            @error('destination') <small class="ref-field-error">{{ $message }}</small> @enderror
                        </div>
                    </section>

                    <section class="ref-panel-card ref-form-card ref-combobox" x-data @click.outside="$wire.closeCustomerDropdown()">
                        <div class="ref-section-heading"><div><span class="ref-step">3</span><h2>Customer details</h2></div></div>
                        <div class="ref-form-grid">
                            <label class="ref-field"><span>Phone Number <b>*</b></span><input type="tel" inputmode="tel" autocomplete="new-password" data-1p-ignore="true" wire:model.live.debounce.350ms="customerPhone" wire:keydown.enter.prevent="findCustomerByPhone" placeholder="Enter the complete registered phone number"></label>
                            <label class="ref-field"><span>Email Address <em>Optional</em></span><input type="email" wire:model.live.debounce.350ms="customerEmail" placeholder="Search registered email"></label>
                            <label class="ref-field"><span>First Name</span><input type="text" wire:model.live.debounce.350ms="customerFirstName" placeholder="Search first name"></label>
                            <label class="ref-field"><span>Last Name</span><input type="text" wire:model.live.debounce.350ms="customerLastName" placeholder="Search last name"></label>
                        </div>
                        @if ($showCustomerDropdown && $customerUserId === null && $customerOptions->isNotEmpty())
                            <div class="ref-search-dropdown ref-customer-search-dropdown" role="listbox">
                                @foreach ($customerOptions as $customer)
                                    <button type="button" role="option" class="ref-receiver-option" wire:click="selectCustomer({{ $customer['id'] }})">
                                        <span class="ref-option-content"><strong>{{ $customer['name'] ?: 'Besmani User' }}</strong><small>Phone: {{ $customer['phone'] }} · Email: {{ $customer['email'] }}</small></span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        @if ($customerUserId)
                            <div class="ref-selected-user"><i class="fa fa-check-circle"></i><span>{{ trim($customerFirstName.' '.$customerLastName) }} — Registered Besmani User found</span></div>
                        @elseif (strlen(preg_replace('/\D+/', '', $customerPhone)) >= 10)
                            <div class="ref-inline-empty">
                                <strong>Customer not found on Besmani.</strong>
                                <span>Please ask the Customer to register with Besmani before sending the referral.</span>
                                <button type="button" class="ref-btn ref-btn-secondary " style="background: #15803d;
    color: #fff;" wire:click="openInvite('customer')" wire:loading.attr="disabled" wire:target="openInvite('customer')"><span wire:loading.remove wire:target="openInvite('customer')">Invite Customer to Besmani</span><span wire:loading wire:target="openInvite('customer')"><i class="fa fa-circle-o-notch fa-spin"></i> Creating invitation...</span></button>
                                @error('invitationRecipient') <small class="ref-field-error">{{ $message }}</small> @enderror
                            </div>
                        @endif
                        @error('customerUserId') <small class="ref-field-error">{{ $message }}</small> @enderror
                    </section>    
  
                    <section class="ref-panel-card ref-form-card">
                        <div class="ref-section-heading"><div><span class="ref-step">4</span><h2>Referral details</h2></div></div>
                        <div class="ref-form-grid">
                            <label class="ref-field"> 
                                <span>Service</span>
                                <select wire:model="serviceId" @disabled($destination === '')>
                                    <option value="">{{ $destination === '' ? 'Select a Provider first' : 'Select a Service' }}</option>
                                    @foreach ($serviceOptions as $service)
                                        <option value="{{ $service['key'] }}">{{ $service['title'] }}

 @if(isset($service['bc']) && $service['bc'] !== null && $service['bc'] !== '')
            - {{ $service['bc'] }} BC
        @endif
 
                                        </option>
                                     @endforeach
                                </select>
                                @if ($destination !== '' && $serviceOptions->count() === 1) <small class="ref-field-hint">This destination has no referral-enabled Services. General Referral is available.</small> @endif
                                @error('serviceId') <small class="ref-field-error">{{ $message }}</small> @enderror
                            </label>
                            @php
                                $selectedService = $serviceOptions->firstWhere('key', $serviceId);
                            @endphp
                            @if ($selectedService)
                                <div class="ref-agreement-note ref-field-wide">
                                    <i class="fa fa-lock"></i>
                                    <span><strong>Referral Reward: {{ number_format($selectedService['reward_bc']) }} BC</strong><br>
                                        Customer Discount:
                                        @if ($selectedService['discount_type'] === 'percentage')
                                            {{ rtrim(rtrim(number_format($selectedService['discount_value'], 2), '0'), '.') }}%
                                        @elseif ($selectedService['discount_type'] === 'fixed')
                                            {{ $selectedService['discount_currency'] ?: '$' }}{{ number_format($selectedService['discount_value'], 2) }}
                                        @else
                                            No Discount
                                        @endif
                                    </span>
                                </div>
                            @endif
                            <label class="ref-field ref-field-wide" wire:key="referral-short-note-field"><span>Short note</span><textarea wire:model="note" rows="3" placeholder="Add helpful details for the destination Provider"></textarea>@error('note') <small class="ref-field-error">{{ $message }}</small> @enderror</label>
                        </div> 
                        <div class="ref-agreement-note"><i class="fa fa-star"></i><span>Reward and discount are read-only and locked when the referral is created.</span></div>
                    </section> 

                    <div class="ref-form-actions">
                        <a href="{{ route('panel.referral', $referralQuery) }}" class="ref-btn ref-btn-secondary">Cancel</a>
                        <button type="submit" class="ref-btn ref-btn-primary" @disabled($destination === '' || $customerUserId === null || $serviceId === null || $serviceId === '')><i class="fa fa-paper-plane"></i> Send Referral</button>
                    </div>
                </form>
            @else 
                <section class="ref-summary-grid {{ $isProvider ? '' : 'is-personal' }}">
 
                    @if ($isProvider)
                        <button type="button" wire:click="setListTab('incoming')" class="ref-summary-card is-blue {{ $listTab === 'incoming' ? 'active' : '' }}"><span class="ref-summary-icon"><i class="fa fa-arrow-down"></i></span><div><p>Incoming</p><strong>{{ $counts['incoming'] }}</strong><small>Connected to you</small></div></button>
                        <button type="button" wire:click="setListTab('outgoing')" class="ref-summary-card is-orange {{ $listTab === 'outgoing' ? 'active' : '' }}"><span class="ref-summary-icon"><i class="fa fa-arrow-up"></i></span><div><p>Outgoing</p><strong>{{ $counts['outgoing'] }}</strong><small>Sent by you</small></div></button>
                    @else
                        <button type="button" wire:click="setListTab('outgoing')" class="ref-summary-card is-orange {{ $listTab === 'outgoing' ? 'active' : '' }}"><span class="ref-summary-icon"><i class="fa fa-exchange"></i></span><div><p>Outgoing</p><strong>{{ $counts['outgoing'] }}</strong><small>Created by you</small></div></button>
                    @endif
                    <button
    type="button"
    wire:click="setListTab('my-referrals')"
    class="ref-summary-card is-blue {{ $listTab === 'my-referrals' ? 'active' : '' }}"
>
    <span class="ref-summary-icon">
        <i class="fa fa-user"></i>
    </span>

    <div>
        <p>My Referrals</p>

        <strong>
            {{ $counts['my_referrals'] }}
        </strong>

        <small>Referrals made for me</small>
    </div>
</button>
                   <button type="button"
    wire:click="setListTab('pending')"
    class="ref-summary-card is-yellow {{ $listTab === 'pending' ? 'active' : '' }}"><span class="ref-summary-icon"><i class="fa fa-clock-o"></i></span><div><p>Pending</p><strong>{{ $counts['pending'] }}</strong><small>Awaiting action</small></div></button>
                    <button type="button" wire:click="setListTab('completed')" class="ref-summary-card is-green {{ $listTab === 'completed' ? 'active' : '' }}"><span class="ref-summary-icon"><i class="fa fa-check"></i></span><div><p>Completed</p><strong>{{ $counts['completed'] }}</strong><small>Service delivered</small></div></button>
                    <button type="button" wire:click="setListTab('coin')" class="ref-summary-card is-purple {{ $listTab === 'coin' ? 'active' : '' }}"><span class="ref-summary-icon"><i class="fa fa-star"></i></span><div><p>{{ $isProvider ? 'COIN Balance' : 'My COIN' }}</p><strong>{{ number_format($coinBalance) }}</strong><small>Besmani COIN (BC)</small></div></button>
                </section>
   
                <section class="ref-panel-card">
                    <div class="ref-section-heading">
                        <div>
                            <span class="ref-eyebrow">Filtered referral history</span>
                            <h2>
    @if ($listTab === 'coin')
        COIN Balance Referrals
    @elseif ($listTab === 'my-referrals')
        My Referrals
    @else
        {{ ucfirst($listTab) }} Referrals
    @endif
</h2>
                        </div>
                    </div>

                    @if ($listTab === 'coin')
                        <div class="ref-coin-summary" aria-label="Filtered Besmani COIN summary">
                            @foreach (['incoming' => 'Incoming', 'outgoing' => 'Outgoing', 'pending' => 'Pending', 'completed' => 'Completed', 'total' => 'Total BC'] as $key => $label)
                                <div class="{{ $key === 'total' ? 'is-total' : '' }}">
                                    <span>{{ $label }}</span>
                                    <strong>{{ number_format($coinSummary[$key]) }} BC</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="ref-filter-grid">
                        <label class="ref-field"><span>{{ $listTab === 'incoming' ? 'Who sent it to me' : 'Who I sent it to' }}</span><input type="search" wire:model.live.debounce.350ms="partyFilter" placeholder="Name or business"></label>
                        <label class="ref-field"><span>Customer phone</span><input type="tel" wire:model.live.debounce.350ms="phoneFilter" placeholder="Phone number"></label>
                        <label class="ref-field"><span>From date</span><input type="date" wire:model.live="dateFrom"></label>
                        <label class="ref-field"><span>To date</span><input type="date" wire:model.live="dateTo"></label>
                        <label class="ref-field"><span>Per page</span><select wire:model.live="perPage"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select></label>
                        <button type="button" class="ref-btn ref-btn-secondary ref-filter-submit" wire:click="resetFilters"><i class="fa fa-refresh"></i> Clear filters</button>
                    </div>
 
                    @if ($referrals->isEmpty())
                        <x-referrals.ui-state type="empty" title="No referrals yet" message="New referral activity will appear here." />
                    @else 
                        <div class="ref-table-wrap">
                            <table class="ref-table">
                                <thead><tr><th>Customer</th><th>{{ $listTab === 'incoming' ? 'Referred By' : 'Destination' }}</th><th>Service / Note</th><th>Status</th><th>BC</th><th>Date</th><th class="ref-table-action-heading">Action</th></tr></thead>
                                <tbody> 
                                    @foreach ($referrals as $referral) 
                                        @php
                                            $isIncoming = $isProvider && ((int) $referral->receiver_user_id === (int) Auth::guard('mainUsers')->id() || in_array((int) $referral->receiver_business_id, $businessIds, true));
                                            $customerName = trim($referral->customer_first_name . ' ' . $referral->customer_last_name);
                                            $destinationName = $isIncoming
                                                ? ($referral->referrerBusiness?->name ?? ($referral->referrerUser ? trim($referral->referrerUser->fl_name . ' ' . $referral->referrerUser->last_name) : 'Business'))
                                                : ($referral->receiverBusiness?->name ?? ($referral->receiverUser ? trim($referral->receiverUser->fl_name . ' ' . $referral->receiverUser->last_name) : 'Business'));
                                        @endphp
                                        <tr wire:key="referral-{{ $referral->id }}">
                                            <td><span class="ref-person-cell"><span class="ref-avatar">{{ strtoupper(substr($customerName ?: 'C', 0, 1)) }}</span><span><strong>{{ $customerName ?: 'Customer' }}</strong><small>{{ $referral->referral_number }}</small></span></span></td>
                                            <td>{{ $destinationName ?: 'Provider' }}</td>
                                            <td>{{ $referral->service_title ?? 'General referral' }} / {{ $referral->note ?? '-' }} </td>
                                            <td><x-referrals.status-badge :status="$referral->status" /></td>
                                            <td><strong>{{ in_array(strtolower(trim($referral->status)), ['completed', 'complete', 'settled'], true) ? number_format($referral->token_amount) . ' BC' : '—' }}</strong></td>
                                            <td>{{ $referral->created_at?->format('M d, Y') }}</td>
                                            <td class="ref-table-actions">
                                                <button type="button" class="ref-action-btn is-view" wire:click="viewReferral({{ $referral->id }})"><i class="fa fa-eye"></i> View</button>
                                                @if ($isIncoming && $referral->status === 'pending')
                                                    <button type="button" class="ref-action-btn is-accept" wire:click="requestAction('accept', {{ $referral->id }})"><i class="fa fa-check"></i> Accept</button>
                                                @elseif ($isIncoming && $referral->status === 'accepted')
                                                    <button type="button" class="ref-action-btn is-complete" wire:click="requestAction('complete', {{ $referral->id }})"><i class="fa fa-check-circle"></i> Complete</button>
                                                @elseif (! $isIncoming && $referral->status === 'pending')
                                                    <button type="button" class="ref-action-btn is-cancel" wire:click="requestAction('cancel', {{ $referral->id }})"><i class="fa fa-times"></i> Cancel</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="ref-pagination">{{ $referrals->links() }}</div>
                    @endif
                </section>
            @endif
        </div>

        @if ($inviteModal)
            <div class="ref-modal-backdrop" wire:click.self="closeInvite">
                <div class="ref-modal">
                    <button type="button" class="ref-modal-close" wire:click="closeInvite"><i class="fa fa-times"></i></button>
                    <span class="ref-modal-icon"><i class="fa fa-user-plus"></i></span>
                    <h2>Invite {{ ucfirst($inviteModal) }} to Besmani</h2>
                    <p id="ref-invite-message">{{ $inviteModal === 'provider' ? 'Join Besmani as a Provider or Business so I can send registered referrals to you.' : 'Please register with Besmani so I can connect you with trusted Providers through a secure referral.' }}</p>
                    <label class="ref-field ref-invite-recipient"><span>{{ $inviteModal === 'provider' ? 'Provider Email or Phone' : 'Customer Email or Phone' }}</span><input type="text" wire:model="invitationRecipient" readonly>@error('invitationRecipient') <small class="ref-field-error">{{ $message }}</small> @enderror</label>
                    <label class="ref-field" wire:key="invitation-link-field-{{ md5($invitationUrl) }}"><span>Invitation Link</span><div class="ref-combobox-control"><input id="ref-invitation-url" type="text" value="{{ $invitationUrl }}" readonly></div></label>
                    <div class="ref-modal-actions" wire:key="invitation-link-actions-{{ md5($invitationUrl) }}">
                            <button type="button" class="ref-btn ref-btn-secondary"
                                    x-data="{ copied: false }"
                                    @click="
                                        const input = document.getElementById('ref-invitation-url');
                                        if (!input || !input.value) return;
                                        const copy = navigator.clipboard && window.isSecureContext
                                            ? navigator.clipboard.writeText(input.value)
                                            : new Promise((resolve, reject) => {
                                                input.focus(); input.select();
                                                document.execCommand('copy') ? resolve() : reject();
                                            });
                                        copy.then(() => { copied = true; setTimeout(() => copied = false, 2000) });
                                    ">
                                <i class="fa" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                                <span x-text="copied ? 'Copied!' : 'Copy Link'">Copy Link</span>
                            </button>
                        {{-- <button type="button" class="ref-btn ref-btn-secondary" wire:click="sendInvitation('sms')" wire:loading.attr="disabled"><i class="fa fa-comment"></i> Text / SMS</button> --}}
                        {{-- <button type="button" class="ref-btn ref-btn-secondary" wire:click="sendInvitation('email')" wire:loading.attr="disabled"><i class="fa fa-envelope"></i> Email</button> --}}
                        <button type="button" class="ref-btn ref-btn-primary" x-data @click="navigator.share ? navigator.share({ title: 'Join Besmani', text: document.getElementById('ref-invite-message').innerText, url: document.getElementById('ref-invitation-url').value }) : navigator.clipboard.writeText(document.getElementById('ref-invitation-url').value)"><i class="fa fa-share-alt"></i> Share</button>
                    </div>
                </div>
            </div>
        @endif

        @if ($showUseCoinsModal)
            <div class="ref-modal-backdrop" wire:click.self="$set('showUseCoinsModal', false)">
                <section class="ref-modal" role="dialog" aria-modal="true">
                    <button class="ref-modal-close" wire:click="$set('showUseCoinsModal', false)"><i class="fa fa-times"></i></button>
                    <span class="ref-modal-icon"><i class="fa fa-star"></i></span><h2>Use Besmani COIN</h2>
                    <p>Your current balance is <strong>{{ number_format($coinBalance) }} BC</strong>. Spending is informational in this MVP and does not deduct BC.</p>
                    <div class="ref-coin-use-grid"><span><i class="fa fa-bullhorn"></i>Advertising Credit</span><span><i class="fa fa-percent"></i>Promotional Discount</span><span><i class="fa fa-star"></i>Featured Listing</span><span><i class="fa fa-briefcase"></i>Besmani Services</span></div>
                    <button class="ref-btn ref-btn-primary" wire:click="$set('showUseCoinsModal', false)">Got it</button>
                </section>
            </div>
        @endif

        @if ($showConfirmation)
            <div class="ref-modal-backdrop" wire:click.self="$set('showConfirmation', false)">
                <section class="ref-modal" role="dialog" aria-modal="true">
                    <span class="ref-modal-icon"><i class="fa {{ $pendingAction === 'cancel' ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i></span>
                    <h2>{{ ucfirst($pendingAction ?? '') }} referral?</h2>
                    <p>{{ $pendingAction === 'complete' ? 'Completing this referral will award the configured BC to the referrer.' : 'Please confirm this referral status action.' }}</p>
                    <div class="ref-modal-actions"><button class="ref-btn ref-btn-secondary" wire:click="$set('showConfirmation', false)">Back</button><button class="ref-btn ref-btn-primary" wire:click="confirmAction">Confirm</button></div>
                </section>
            </div>
        @endif
 
        @if ($selectedReferral)
            @php
                $appointmentPhone = collect([
                    $selectedReferral->receiverBusiness?->phone,
                    $selectedReferral->receiverUser?->mobile,
                    $selectedReferral->receiverUser?->phone,
                ])->map(fn ($phone) => trim((string) $phone))->first(fn ($phone) => $phone !== '') ?? '';
                $appointmentDoctorName = trim((string) ($selectedReferral->receiverUser
                    ? trim($selectedReferral->receiverUser->fl_name . ' ' . $selectedReferral->receiverUser->last_name)
                    : ''));
                $appointmentDestinationName = $appointmentDoctorName !== ''
                    ? $appointmentDoctorName
                    : trim((string) ($selectedReferral->receiverBusiness?->name
                        ?? $selectedReferral->receiverBusiness?->title
                        ?? ''));
            @endphp 
            <div class="ref-modal-backdrop" wire:click.self="closeReferral" x-data="{ appointmentContactOpen: false }" @keydown.escape.window="appointmentContactOpen = false">
                <section class="ref-modal ref-detail-modal" role="dialog" aria-modal="true">
                    <button class="ref-modal-close" wire:click="closeReferral"><i class="fa fa-times"></i></button>
                    <span class="ref-eyebrow">{{ $selectedReferral->referral_number }}</span><h2>{{ trim($selectedReferral->customer_first_name . ' ' . $selectedReferral->customer_last_name) }}</h2>
                    <x-referrals.status-badge :status="$selectedReferral->status" />
                    <div class="ref-detail-list"><span>Phone<strong>{{ $selectedReferral->customer_phone }}</strong></span><span>Email<strong>{{ $selectedReferral->customer_email ?: '—' }}</strong></span><span>Service<strong>{{ $selectedReferral->service_title ?? 'General referral' }}</strong></span><span>Besmani COIN<strong>{{ in_array(strtolower(trim($selectedReferral->status)), ['completed', 'complete', 'settled'], true) ? number_format($selectedReferral->token_amount) . ' BC' : 'Awarded after completion' }}</strong></span></div>
                    @if ($selectedReferral->note)<div class="ref-agreement-note"><i class="fa fa-sticky-note"></i><span>{{ $selectedReferral->note }}</span></div>@endif
               
                    @if ($selectedReferral->status === 'accepted')
                        <div class="ref-modal-actions appointment">
                            <button type="button" class="ref-appointment-btn" @click="appointmentContactOpen = true">
                                <i class="fa fa-calendar-check-o"></i>
                                Book Appointment
                            </button>
                        </div>

                        <div class="ref-modal-backdrop" x-cloak x-show="appointmentContactOpen" @click.self="appointmentContactOpen = false">
                            <section class="ref-modal" role="dialog" aria-modal="true" aria-labelledby="appointment-contact-title">
                                <button type="button" class="ref-modal-close" @click="appointmentContactOpen = false" aria-label="Close appointment contact details"><i class="fa fa-times"></i></button>
                                <span class="ref-modal-icon"><i class="fa fa-phone"></i></span>
                                <h2 id="appointment-contact-title">Book an Appointment</h2>
                                @if ($appointmentPhone !== '')
                                    <p>To book an appointment, please call dr {{ $appointmentDestinationName !== '' ? ': ' . $appointmentDestinationName : '' }}</p>
                                    <p class="call-dr-ref"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $appointmentPhone) }}"><strong>{{ $appointmentPhone }}</strong></a></p>
                                @else
                                    <p>No phone number is available for this Referral destination. Please contact them directly to book an appointment.</p>
                                @endif
                                <div class="ref-modal-actions">
                                    <button type="button" class="ref-btn ref-btn-secondary" @click="appointmentContactOpen = false">Close</button>
                                </div>
                            </section>
                        </div>
                    @endif
              
                </section>
             
            </div>
            
        @endif

        @if ($successMessage)
            <div class="ref-toast is-success"><i class="fa fa-check-circle"></i><div><strong>Success</strong><span>{{ $successMessage }}</span></div><button wire:click="closeSuccess" aria-label="Close"><i class="fa fa-times"></i></button></div>
        @endif
    </main>
</div>
