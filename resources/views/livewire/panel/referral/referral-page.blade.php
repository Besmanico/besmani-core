<div>
    <link rel="stylesheet" href="{{ asset('assets-file/css/referrals.css') }}">

    <main class="panel-main referral-shell">
        <header class="ref-page-header">
            <div>
                <span class="ref-eyebrow">Besmani Referral Network</span>
                <h1>{{ $isProvider ? 'Business Referrals' : 'My Referrals' }}</h1>
                <p>{{ $isProvider ? 'Manage referrals connected to you and your authorized businesses.' : 'Refer someone to a Besmani Provider and earn Besmani COIN after completion.' }}</p>
            </div>
            <div class="ref-header-actions">
                @if ($isProvider)
                    <button type="button" class="ref-btn ref-btn-secondary" wire:click="$set('showUseCoinsModal', true)">
                        <i class="fa fa-star"></i> Use BC
                    </button>
                @endif
                <a href="{{ route('panel.referral.new') }}" class="ref-btn ref-btn-primary">
                    <i class="fa fa-exchange"></i>
                    {{ $isProvider ? 'New Referral' : 'Refer Someone' }}
                </a>
            </div>
        </header>  
  
        <div class="ref-token-notice">
            <i class="fa fa-shield"></i>
            <p><strong>Besmani COIN (BC) is an internal, non-cash credit.</strong> BC is awarded only after a referral is Completed. It cannot be transferred, withdrawn, converted to cash, or used as cryptocurrency.</p>
        </div>

        <nav class="ref-subnav {{ $isProvider ? '' : 'is-personal' }}" aria-label="Referral sections">
            <a href="{{ route('panel.referral') }}" class="{{ $section === 'dashboard' ? 'active' : '' }}"><i class="fa fa-th-large"></i><span>Overview</span></a>
            @if ($isProvider)
                <a href="{{ route('panel.referral.incoming') }}" class="{{ $section === 'incoming' ? 'active' : '' }}"><i class="fa fa-arrow-down"></i><span>Incoming</span></a>
                <a href="{{ route('panel.referral.outgoing') }}" class="{{ $section === 'outgoing' ? 'active' : '' }}"><i class="fa fa-arrow-up"></i><span>Outgoing</span></a>
            @endif
            <a href="{{ route('panel.referral.new') }}" class="{{ $section === 'new' ? 'active' : '' }}"><i class="fa fa-exchange"></i><span>New Referral</span></a>
        </nav>       

        <div class="ref-page-content" wire:loading.class="is-loading" wire:target="createReferral,confirmAction,viewReferral">
            <div class="ref-loading-overlay" wire:loading.flex wire:target="createReferral,confirmAction,viewReferral">
                <x-referrals.ui-state type="loading" title="Please wait" message="Updating your referral information..." />
            </div>

            @if ($section === 'new') 
                <form wire:submit="createReferral" class="ref-form-layout">
                    <section class="ref-panel-card ref-form-card"> 
                          <div class="ref-section-heading">
                            <div><span class="ref-step">1</span><h2>Referral From</h2></div>
                            <p>Choose a Provider or Business that will receive the customer.</p>
                        </div>  
 @if ($isProvider && $businesses->isNotEmpty())  
                            <label class="ref-field">
                                <span>  who is sending this</span>
                                <select wire:model="referringAccount">
                                    <option value="personal">Select the person or  business sending this referral</option>
                                    @foreach ($businesses as $business)
                                        <option value="business:{{ $business->id }}">{{ $business->name ?? $business->title ?? 'Business' }}</option>
                                    @endforeach 
                                </select>
                                @error('referringAccount') <small class="ref-field-error">{{ $message }}</small> @enderror
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
                                                    <button type="button" class="ref-btn ref-btn-secondary" wire:click="openInvite('provider')">Invite Provider</button>
                                                </div>
                                            @endforelse
                                        </div>
                                    @endif
                                @endif
                            </div>
                            @error('destination') <small class="ref-field-error">{{ $message }}</small> @enderror
                        </div>
                    </section>

                    <section class="ref-panel-card ref-form-card">
                        <div class="ref-section-heading"><div><span class="ref-step">2</span><h2>Customer details</h2></div></div>
                        <div class="ref-form-grid">
                            <label class="ref-field"><span>Phone Number <b>*</b></span><input type="tel" inputmode="tel" autocomplete="new-password" data-1p-ignore="true" wire:model.live.debounce.350ms="customerPhone" wire:keydown.enter.prevent="findCustomerByPhone" placeholder="Enter the complete registered phone number"></label>
                            <label class="ref-field"><span>Email <em>Optional</em></span><input type="email" wire:model="customerEmail" placeholder="Filled automatically" readonly></label>
                            <label class="ref-field"><span>First Name</span><input type="text" wire:model="customerFirstName" placeholder="Filled after selecting the User" readonly></label>
                            <label class="ref-field"><span>Last Name</span><input type="text" wire:model="customerLastName" placeholder="Filled after selecting the User" readonly></label>
                        </div>
                        @if ($customerUserId)
                            <div class="ref-selected-user"><i class="fa fa-check-circle"></i><span>{{ trim($customerFirstName.' '.$customerLastName) }} — Registered Besmani User found</span></div>
                        @elseif (strlen(preg_replace('/\D+/', '', $customerPhone)) >= 10)
                            <div class="ref-inline-empty">
                                <strong>Customer not found on Besmani.</strong>
                                <span>Please ask the Customer to register with Besmani before sending the referral.</span>
                                <button type="button" class="ref-btn ref-btn-secondary" wire:click="openInvite('customer')">Invite Customer to Besmani</button>
                            </div>
                        @endif
                        @error('customerUserId') <small class="ref-field-error">{{ $message }}</small> @enderror
                    </section>

                    <section class="ref-panel-card ref-form-card">
                        <div class="ref-section-heading"><div><span class="ref-step">3</span><h2>Referral details</h2></div></div>
                        <div class="ref-form-grid">
                            <label class="ref-field">
                                <span>Service</span>
                                <select wire:model="serviceId" @disabled($destination === '')>
                                    <option value="">{{ $destination === '' ? 'Select a Provider first' : 'General Referral' }}</option>
                                </select>
                                @if ($destination !== '') <small class="ref-field-hint">This Provider has no mapped active services available. The referral will be sent as General Referral.</small> @endif
                                @error('serviceId') <small class="ref-field-error">{{ $message }}</small> @enderror
                            </label>
                            <label class="ref-field ref-field-wide"><span>Short note</span><textarea wire:model="note" rows="3" placeholder="Add helpful details for the destination Provider"></textarea>@error('note') <small class="ref-field-error">{{ $message }}</small> @enderror</label>
                        </div> 
                        <div class="ref-agreement-note"><i class="fa fa-star"></i><span>The BC award is controlled by Besmani configuration. Neither the User nor Provider can enter or modify it.</span></div>
                    </section>

                    <div class="ref-form-actions">
                        <a href="{{ route('panel.referral') }}" class="ref-btn ref-btn-secondary">Cancel</a>
                        <button type="submit" class="ref-btn ref-btn-primary" @disabled($destination === '' || $customerUserId === null)><i class="fa fa-paper-plane"></i> Send Referral</button>
                    </div>
                </form>
            @else
                <section class="ref-summary-grid {{ $isProvider ? '' : 'is-personal' }}">
                    @if ($isProvider)
                        <article class="ref-summary-card is-blue"><span class="ref-summary-icon"><i class="fa fa-arrow-down"></i></span><div><p>Incoming</p><strong>{{ $counts['incoming'] }}</strong><small>Connected to you</small></div></article>
                        <article class="ref-summary-card is-orange"><span class="ref-summary-icon"><i class="fa fa-arrow-up"></i></span><div><p>Outgoing</p><strong>{{ $counts['outgoing'] }}</strong><small>Sent by you</small></div></article>
                    @else
                        <article class="ref-summary-card is-blue"><span class="ref-summary-icon"><i class="fa fa-exchange"></i></span><div><p>My Referrals</p><strong>{{ $counts['all'] }}</strong><small>Created by you</small></div></article>
                    @endif
                    <article class="ref-summary-card is-orange"><span class="ref-summary-icon"><i class="fa fa-clock-o"></i></span><div><p>Pending</p><strong>{{ $counts['pending'] }}</strong><small>Awaiting action</small></div></article>
                    <article class="ref-summary-card is-green"><span class="ref-summary-icon"><i class="fa fa-check"></i></span><div><p>Completed</p><strong>{{ $counts['completed'] }}</strong><small>Service delivered</small></div></article>
                    <article class="ref-summary-card is-purple"><span class="ref-summary-icon"><i class="fa fa-star"></i></span><div><p>{{ $isProvider ? 'COIN Balance' : 'My COIN' }}</p><strong>{{ number_format($coinBalance) }}</strong><small>Besmani COIN (BC)</small></div></article>
                </section>

                <section class="ref-panel-card">
                    <div class="ref-section-heading">
                        <div>
                            <span class="ref-eyebrow">{{ $section === 'incoming' ? 'Addressed to you' : ($section === 'outgoing' ? 'Created by you' : 'Recent activity') }}</span>
                            <h2>{{ $section === 'incoming' ? 'Incoming Referrals' : ($section === 'outgoing' ? 'Outgoing Referrals' : ($isProvider ? 'Provider Referrals' : 'My Referrals')) }}</h2>
                        </div>
                    </div>

                    @if ($referrals->isEmpty())
                        <x-referrals.ui-state type="empty" title="No referrals yet" message="New referral activity will appear here." />
                    @else
                        <div class="ref-table-wrap">
                            <table class="ref-table">
                                <thead><tr><th>Customer</th><th>{{ $section === 'incoming' ? 'Referred By' : 'Destination' }}</th><th>Service / Note</th><th>Status</th><th>BC</th><th>Date</th><th class="ref-table-action-heading">Action</th></tr></thead>
                                <tbody>
                                    @foreach ($referrals as $referral)
                                        @php
                                            $isIncoming = $isProvider && ((int) $referral->receiver_user_id === (int) Auth::guard('mainUsers')->id() || in_array((int) $referral->receiver_business_id, $businessIds, true));
                                            $customerName = trim($referral->customer_first_name . ' ' . $referral->customer_last_name);
                                            $destinationName = $isIncoming
                                                ? ($referral->referrerUser ? trim($referral->referrerUser->fl_name . ' ' . $referral->referrerUser->last_name) : 'Business')
                                                : ($referral->receiverUser ? trim($referral->receiverUser->fl_name . ' ' . $referral->receiverUser->last_name) : 'Business');
                                        @endphp
                                        <tr wire:key="referral-{{ $referral->id }}">
                                            <td><span class="ref-person-cell"><span class="ref-avatar">{{ strtoupper(substr($customerName ?: 'C', 0, 1)) }}</span><span><strong>{{ $customerName ?: 'Customer' }}</strong><small>{{ $referral->referral_number }}</small></span></span></td>
                                            <td>{{ $destinationName ?: 'Provider' }}</td>
                                            <td>{{ $referral->service?->title ?? ($referral->note ?: 'General referral') }}</td>
                                            <td><x-referrals.status-badge :status="$referral->status" /></td>
                                            <td><strong>{{ $referral->status === 'completed' ? number_format($referral->token_amount) . ' BC' : '—' }}</strong></td>
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
                    <div class="ref-modal-actions">
                        <button type="button" class="ref-btn ref-btn-primary" onclick="navigator.clipboard.writeText(document.getElementById('ref-invite-message').innerText)"><i class="fa fa-copy"></i> Copy invitation</button>
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
            <div class="ref-modal-backdrop" wire:click.self="closeReferral">
                <section class="ref-modal ref-detail-modal" role="dialog" aria-modal="true">
                    <button class="ref-modal-close" wire:click="closeReferral"><i class="fa fa-times"></i></button>
                    <span class="ref-eyebrow">{{ $selectedReferral->referral_number }}</span><h2>{{ trim($selectedReferral->customer_first_name . ' ' . $selectedReferral->customer_last_name) }}</h2>
                    <x-referrals.status-badge :status="$selectedReferral->status" />
                    <div class="ref-detail-list"><span>Phone<strong>{{ $selectedReferral->customer_phone }}</strong></span><span>Email<strong>{{ $selectedReferral->customer_email ?: '—' }}</strong></span><span>Service<strong>{{ $selectedReferral->service?->title ?? 'General referral' }}</strong></span><span>Besmani COIN<strong>{{ $selectedReferral->status === 'completed' ? number_format($selectedReferral->token_amount) . ' BC' : 'Awarded after completion' }}</strong></span></div>
                    @if ($selectedReferral->note)<div class="ref-agreement-note"><i class="fa fa-sticky-note"></i><span>{{ $selectedReferral->note }}</span></div>@endif
                </section>
            </div>
        @endif

        @if ($successMessage)
            <div class="ref-toast is-success"><i class="fa fa-check-circle"></i><div><strong>Success</strong><span>{{ $successMessage }}</span></div><button wire:click="closeSuccess" aria-label="Close"><i class="fa fa-times"></i></button></div>
        @endif
    </main>
</div>
