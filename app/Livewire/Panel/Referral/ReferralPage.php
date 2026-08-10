<?php

namespace App\Livewire\Panel\Referral;

use App\Models\InfoActivity;
use App\Models\MainUser;
use App\Models\Referral;
use App\Models\ReferralStatusHistory;
use App\Models\TokenLedger;
use App\Services\Referrals\ReferralAccessService;
use App\Services\Referrals\ReferralInvitationService;
use App\Services\Referrals\ReferralServiceCatalog;
use App\Services\Referrals\ReferralWorkflowService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ReferralPage extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $section = 'dashboard';

    public string $listTab = 'incoming';

    public string $partyFilter = '';

    public string $phoneFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public int $perPage = 10;

    public bool $isProvider = false;

    public bool $showUseCoinsModal = false;

    public bool $showConfirmation = false;

    public ?string $pendingAction = null;

    public ?int $pendingReferralId = null;

    public ?int $selectedReferralId = null;

    public ?string $successMessage = null;

    public string $receiverSearch = '';

    public string $referringAccount = 'personal';

    public string $destination = '';

    public string $selectedDestinationName = '';

    public string $selectedDestinationDetails = '';

    public bool $showDestinationDropdown = false;

    public string $customerFirstName = '';

    public string $customerLastName = '';

    public string $customerPhone = '';

    public string $customerEmail = '';

    public ?int $customerUserId = null;

    public ?string $inviteModal = null;

    public string $invitationRecipient = '';

    public ?string $serviceId = null;

    public string $note = '';

    public function mount(string $section = 'dashboard'): void
    {
        $user = $this->user();
        $this->isProvider = app(ReferralAccessService::class)->isProvider($user);

        $allowedSections = $this->isProvider
            ? ['dashboard', 'incoming', 'outgoing', 'new']
            : ['dashboard', 'new'];

        abort_unless(in_array($section, $allowedSections, true), 403);
        if (in_array($section, ['incoming', 'outgoing'], true)) {
            $this->listTab = $section;
            $this->section = 'dashboard';
        } else {
            $this->section = $section;
        }

        if (! $this->isProvider) {
            $this->listTab = 'outgoing';
        }
    }

    public function createReferral(): void
    {
        $user = $this->user();
        $this->authorize('create', Referral::class);

        $this->validate([
            'referringAccount' => ['required', 'string'],
            'destination' => ['required', 'string'],
            'customerUserId' => ['required', 'integer'],
            'serviceId' => ['nullable'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        [$destinationType, $destinationId] = $this->parseAccountValue($this->destination);
        [$referrerType, $referrerId] = $this->parseAccountValue($this->referringAccount);

        abort_unless($destinationType === 'business', 422);

        $receiverUserId = null;
        $receiverBusinessId = null;

        $receiverBusiness = InfoActivity::query()->whereKey($destinationId)->first();
        if (! $receiverBusiness || ! MainUser::query()->whereKey($receiverBusiness->user_id)->exists()) {
            throw ValidationException::withMessages([
                'destination' => 'Select an existing registered Besmani Business.',
            ]);
        }
        $receiverBusinessId = (int) $receiverBusiness->getKey();

        $selectedService = app(ReferralServiceCatalog::class)->findForBusiness($receiverBusiness, $this->serviceId);

        if ($this->serviceId !== null && $selectedService === null) {
            throw ValidationException::withMessages([
                'serviceId' => 'The selected service is not available for this destination.',
            ]);
        }

        $customer = MainUser::query()->find($this->customerUserId);
        if (! $customer || ! $this->customerDetailsMatch($customer)) {
            throw ValidationException::withMessages([
                'customerUserId' => 'Select an existing registered Besmani User from the search results.',
            ]);
        }

        $referrerBusinessId = null;
        if ($referrerType === 'business') {
            abort_unless($this->isProvider, 403);
            abort_unless(app(ReferralAccessService::class)->ownsBusiness($user, $referrerId), 403);
            $referrerBusinessId = $referrerId;
        }

        DB::transaction(function () use ($user, $receiverUserId, $receiverBusinessId, $referrerBusinessId, $customer, $selectedService): void {
            $referral = Referral::query()->create([
                'referral_number' => $this->newReferralNumber(),
                'referrer_user_id' => $user->getKey(),
                'referrer_business_id' => $referrerBusinessId,
                'receiver_user_id' => $receiverUserId,
                'receiver_business_id' => $receiverBusinessId,
                'customer_user_id' => $customer->getKey(),
                'customer_first_name' => $customer->fl_name,
                'customer_last_name' => $customer->last_name,
                'customer_phone' => $this->userPhone($customer),
                'customer_email' => $customer->email ?: null,
                'service_id' => $selectedService['id'] ?? null,
                'service_type' => $selectedService['type'] ?? null,
                'service_title' => $selectedService['title'] ?? null,
                'reward_type' => 'besmani_coin',
                'token_amount' => 0,
                'note' => $this->note ?: null,
                'status' => 'pending',
            ]);

            ReferralStatusHistory::query()->create([
                'referral_id' => $referral->getKey(),
                'old_status' => null,
                'new_status' => 'pending',
                'changed_by_user_id' => $user->getKey(),
            ]);
        });

        $this->resetReferralForm();
        $this->successMessage = 'Referral created successfully. BC will be awarded only after completion.';
        $this->section = $this->isProvider ? 'outgoing' : 'dashboard';
    }

    public function requestAction(string $action, int $referralId): void
    {
        abort_unless(in_array($action, ['accept', 'complete', 'cancel'], true), 404);

        $referral = $this->findVisibleReferral($referralId);
        $this->authorize($action, $referral);

        $this->pendingAction = $action;
        $this->pendingReferralId = $referral->getKey();
        $this->showConfirmation = true;
    }

    public function confirmAction(ReferralWorkflowService $workflow): void
    {
        abort_if($this->pendingAction === null || $this->pendingReferralId === null, 422);

        $referral = $this->findVisibleReferral($this->pendingReferralId);
        $this->authorize($this->pendingAction, $referral);

        match ($this->pendingAction) {
            'accept' => $workflow->accept($referral, $this->user()),
            'complete' => $workflow->complete($referral, $this->user()),
            'cancel' => $workflow->cancel($referral, $this->user()),
        };

        $label = ucfirst($this->pendingAction);
        $this->resetActionState();
        $this->successMessage = "Referral {$label} action completed.";
    }

    public function viewReferral(int $referralId): void
    {
        $referral = $this->findVisibleReferral($referralId);
        $this->authorize('view', $referral);
        $this->selectedReferralId = $referral->getKey();
    }

    public function closeReferral(): void
    {
        $this->selectedReferralId = null;
    }

    public function closeSuccess(): void
    {
        $this->successMessage = null;
    }

    public function updatedDestination(): void
    {
        $this->serviceId = null;
        $this->resetValidation(['destination', 'serviceId']);
    }

    public function updatedReceiverSearch(): void
    {
        $this->showDestinationDropdown = true;

        if ($this->destination !== '') {
            $this->destination = '';
            $this->selectedDestinationName = '';
            $this->selectedDestinationDetails = '';
            $this->serviceId = null;
        }
    }

    public function selectDestination(string $value): void
    {
        [$type, $id] = $this->parseAccountValue($value);
        abort_unless($type === 'business', 422);

        $business = InfoActivity::query()->findOrFail($id);
        $provider = MainUser::query()->findOrFail($business->user_id);

        $this->destination = 'business:'.$business->getKey();
        $this->selectedDestinationName = (string) $business->name;
        $this->selectedDestinationDetails = trim(($provider->fl_name ?? '').' '.($provider->last_name ?? ''));
        $this->receiverSearch = (string) $business->name;
        $this->showDestinationDropdown = false;
        $this->serviceId = null;
        $this->resetValidation(['destination', 'serviceId']);
    }

    public function clearDestination(): void
    {
        $this->destination = '';
        $this->selectedDestinationName = '';
        $this->selectedDestinationDetails = '';
        $this->receiverSearch = '';
        $this->showDestinationDropdown = false;
        $this->serviceId = null;
    }

    public function openDestinationDropdown(): void
    {
        $this->showDestinationDropdown = true;
    }

    public function closeDestinationDropdown(): void
    {
        $this->showDestinationDropdown = false;
    }

    public function updatedCustomerPhone(): void
    {
        $this->findCustomerByPhone();
    }

    public function findCustomerByPhone(): void
    {
        $this->customerUserId = null;
        $this->customerEmail = '';
        $this->customerFirstName = '';
        $this->customerLastName = '';

        if (strlen($this->normalizePhone($this->customerPhone)) < 10) {
            return;
        }

        $customer = $this->registeredCustomerByPhone($this->customerPhone);
        if ($customer) {
            $this->selectCustomer((int) $customer->getKey());
        }
    }

    public function selectCustomer($customerId): void
    {
        if (! is_numeric($customerId)) {
            return;
        }

        $customer = MainUser::query()->findOrFail($customerId);

        $this->customerUserId = (int) $customer->getKey();
        $this->customerPhone = $this->userPhone($customer);
        $this->customerEmail = (string) ($customer->email ?? '');
        $this->customerFirstName = (string) ($customer->fl_name ?? '');
        $this->customerLastName = (string) ($customer->last_name ?? '');
        $this->resetValidation('customerUserId');
    }

    public function openInvite(string $party): void
    {
        abort_unless(in_array($party, ['provider', 'customer'], true), 404);
        $this->inviteModal = $party;
        $this->invitationRecipient = trim($party === 'provider' ? $this->receiverSearch : $this->customerPhone);
        $this->resetValidation('invitationRecipient');
    }

    public function closeInvite(): void
    {
        $this->inviteModal = null;
        $this->invitationRecipient = '';
        $this->resetValidation('invitationRecipient');
    }

    public function sendInvitation(ReferralInvitationService $invitations): void
    {
        abort_unless(in_array($this->inviteModal, ['provider', 'customer'], true), 422);

        $recipient = trim($this->invitationRecipient);
        $isEmail = filter_var($recipient, FILTER_VALIDATE_EMAIL) !== false;
        $isPhone = preg_match('/^\+?[0-9][0-9\s()\-]{8,19}$/', $recipient) === 1
            && strlen($this->normalizePhone($recipient)) >= 10;

        if (! $isEmail && ! $isPhone) {
            throw ValidationException::withMessages([
                'invitationRecipient' => 'Enter a valid email address or phone number.',
            ]);
        }
 
        try {
            $invitations->send($this->user(), $isEmail ? $recipient : $this->normalizePhone($recipient), $this->inviteModal);
        } catch (\Throwable $exception) {
            report($exception);
            throw ValidationException::withMessages([
                'invitationRecipient' => 'The invitation could not be sent. Please try again or contact support.',
            ]);
        } 

        $channel = $isEmail ? 'email' : 'SMS'; 
        $this->closeInvite();
        $this->successMessage = "Invitation sent by {$channel}.";
    }

    public function setListTab(string $tab): void
    {
        $allowed = $this->isProvider
            ? ['incoming', 'outgoing', 'pending', 'completed', 'coin']
            : ['outgoing', 'pending', 'completed', 'coin'];
        abort_unless(in_array($tab, $allowed, true), 404);

        $this->listTab = $tab;
        $this->resetFilters();
    }

    public function resetFilters(): void
    {
        $this->reset(['partyFilter', 'phoneFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function updatedPartyFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPhoneFilter(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        if (! in_array($this->perPage, [10, 25, 50], true)) {
            $this->perPage = 10;
        }
        $this->resetPage();
    }

    public function render()
    {
        $user = $this->user();
        $access = app(ReferralAccessService::class);
        $businessIds = $this->isProvider ? $access->authorizedBusinessIds($user) : [];

        $baseQuery = $access->visibleQuery($user);
        $incomingQuery = $this->isProvider ? $access->incomingQuery($user) : Referral::query()->whereRaw('1 = 0');
        $outgoingQuery = $access->outgoingQuery($user);

        $referrals = match ($this->listTab) {
            'incoming' => (clone $incomingQuery),
            'outgoing' => (clone $outgoingQuery),
            'pending' => (clone $baseQuery)->where('status', 'pending'),
            'completed' => (clone $baseQuery)->where('status', 'completed'),
            'coin' => (clone $baseQuery),
            default => (clone $baseQuery),
        };

        $this->applyListFilters($referrals);

        $filteredIncomingQuery = clone $incomingQuery;
        $filteredOutgoingQuery = clone $outgoingQuery;
        $filteredPendingQuery = (clone $baseQuery)->where('status', 'pending');
        $filteredCompletedQuery = (clone $baseQuery)->where('status', 'completed');
        $filteredTotalQuery = clone $baseQuery;
        $this->applyListFilters($filteredIncomingQuery, 'incoming');
        $this->applyListFilters($filteredOutgoingQuery, 'outgoing');
        $this->applyListFilters($filteredPendingQuery);
        $this->applyListFilters($filteredCompletedQuery);
        $this->applyListFilters($filteredTotalQuery);

        $receiverOptions = $this->receiverOptions();
        $serviceOptions = $this->destinationServiceOptions();
        $selectedReferral = $this->selectedReferralId
            ? $this->findVisibleReferral($this->selectedReferralId)->load(['referrerUser', 'receiverUser'])
            : null;

        $coinBalance = TokenLedger::query()
            ->where('status', 'completed')
            ->where(function ($query) use ($user, $businessIds): void {
                $query->where('to_user_id', $user->getKey());
                if ($businessIds !== []) {
                    $query->orWhereIn('to_business_id', $businessIds);
                }
            })
            ->sum('token_amount');

        return view('livewire.panel.referral.referral-page', [
            'referrals' => $referrals->with(['referrerUser', 'referrerBusiness', 'receiverUser', 'receiverBusiness'])->latest()->paginate($this->perPage),
            'counts' => [
                'all' => (clone $baseQuery)->count(),
                'incoming' => $this->isProvider ? (clone $incomingQuery)->count() : 0,
                'outgoing' => (clone $outgoingQuery)->count(),
                'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
                'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            ],
            'coinBalance' => (int) $coinBalance,
            'coinSummary' => [
                'incoming' => (int) $filteredIncomingQuery->sum('token_amount'),
                'outgoing' => (int) $filteredOutgoingQuery->sum('token_amount'),
                'pending' => (int) $filteredPendingQuery->sum('token_amount'),
                'completed' => (int) $filteredCompletedQuery->sum('token_amount'),
                'total' => (int) $filteredTotalQuery->sum('token_amount'),
            ],
            'businessIds' => $businessIds,
            'businesses' => $this->isProvider
                ? InfoActivity::query()->where('user_id', $user->getKey())->get()
                : collect(),
            'referringUserName' => trim((string) ($user->fl_name ?? '').' '.(string) ($user->last_name ?? '')),
            'referringUserId' => trim((int) ($user->id ?? '')),
            'receiverOptions' => $receiverOptions,
            'serviceOptions' => $serviceOptions,
            'selectedReferral' => $selectedReferral,
        ])->layout('components.layouts.panel', ['title' => 'Referrals']);
    }

    private function applyListFilters($query, ?string $direction = null): void
    {
        $term = trim($this->partyFilter);
        if ($term !== '') {
            $relationNames = ($direction ?? $this->listTab) === 'incoming'
                ? ['referrerUser', 'referrerBusiness']
                : ['receiverUser', 'receiverBusiness'];

            $query->where(function ($query) use ($relationNames, $term): void {
                $like = '%'.$term.'%';
                $query->whereHas($relationNames[0], function ($user) use ($like): void {
                    $user->where('fl_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhereRaw("CONCAT(COALESCE(fl_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", [$like]);
                })->orWhereHas($relationNames[1], fn ($business) => $business->where('name', 'like', $like));
            });
        }

        if ($this->phoneFilter !== '') {
            $digits = $this->normalizePhone($this->phoneFilter);
            $query->where('customer_phone', 'like', '%'.substr($digits, -10).'%');
        }
        if ($this->dateFrom !== '') {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo !== '') {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
    }

    private function receiverOptions()
    {
        $term = trim($this->receiverSearch);
        if (mb_strlen($term) < 2) {
            return collect();
        }

        $search = '%'.$term.'%';
        $normalizedTextSearch = '%'.mb_strtolower($term).'%';
        $normalizedPhone = $this->normalizePhone($term);
        $isPhoneSearch = preg_match('/^[\d\s()+-]+$/', $term) === 1;
        if ($isPhoneSearch && strlen($normalizedPhone) < 10) {
            return collect();
        }

        $providerIds = MainUser::query()
            ->where(function ($query) use ($search, $normalizedPhone): void {
                $query->where('fl_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhereRaw("CONCAT(COALESCE(fl_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", [$search]);

                if ($normalizedPhone !== '') {
                    $query->orWhereRaw(
                        "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(mobile, ' ', ''), '-', ''), '(', ''), ')', ''), '+', ''), 10) = ?",
                        [substr($normalizedPhone, -10)]
                    );
                }
            })
            ->pluck('id');

        $matchedBusinessIds = InfoActivity::query()
            ->where(function ($query) use ($normalizedTextSearch, $normalizedPhone): void {
                $query->whereRaw("LOWER(COALESCE(name, '')) LIKE ?", [$normalizedTextSearch])
                    ->orWhereRaw("LOWER(COALESCE(email, '')) LIKE ?", [$normalizedTextSearch])
                    ->orWhereRaw("LOWER(COALESCE(city, '')) LIKE ?", [$normalizedTextSearch])
                    ->orWhereRaw("LOWER(COALESCE(province, '')) LIKE ?", [$normalizedTextSearch]);

                if ($normalizedPhone !== '') {
                    $query->orWhereRaw(
                        "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '(', ''), ')', ''), '+', ''), 10) = ?",
                        [substr($normalizedPhone, -10)]
                    );
                }
            })
            ->pluck('id');

        $providerBusinessIds = InfoActivity::query()
            ->whereIn('user_id', $providerIds)
            ->pluck('id');

        return $this->destinationOptionsForBusinessIds(
            $matchedBusinessIds->concat($providerBusinessIds)->unique()->map(static fn ($id): int => (int) $id)->all()
        );
    }

    private function destinationServiceOptions()
    {
        if ($this->destination === '') {
            return collect();
        }

        [$type, $businessId] = $this->parseAccountValue($this->destination);
        if ($type !== 'business') {
            return collect();
        }

        $business = InfoActivity::query()->find($businessId);

        return $business
            ? app(ReferralServiceCatalog::class)->forBusiness($business)
            : collect();
    }

    private function destinationOptionsForBusinessIds(array $businessIds)
    {
        if ($businessIds === []) {
            return collect();
        }

        $businesses = InfoActivity::query()
            ->whereIn('id', $businessIds)
            ->whereNotNull('name')
            ->where('name', '<>', '')
            ->orderBy('name')
            ->get();

        $providers = MainUser::query()
            ->whereIn('id', $businesses->pluck('user_id')->filter()->unique())
            ->get()
            ->keyBy('id');

        return $businesses
            ->map(function (InfoActivity $infoActivity) use ($providers): array {
                $provider = $providers->get($infoActivity->user_id);
                $providerName = trim(($provider?->fl_name ?? '').' '.($provider?->last_name ?? ''));

                return [
                    'value' => 'business:'.$infoActivity->getKey(),
                    'name' => $infoActivity->name,
                    'details' => collect([$providerName, $infoActivity->city ?? null, $infoActivity->province ?? null])->filter()->join(' · '),
                    'type' => 'Business',
                ];
            });
    }

    private function registeredCustomerByPhone(string $phone): ?MainUser
    {
        return MainUser::query()
            ->whereRaw(
                "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(mobile, ' ', ''), '-', ''), '(', ''), ')', ''), '+', ''), 10) = ?",
                [$this->canonicalPhone($phone)]
            )
            ->first();
    }

    private function activeProviderIds(): array
    {
        return MainUser::query()
            ->where('service_pr', 1)
            ->where('approved', 1)
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }

    private function customerDetailsMatch(MainUser $customer): bool
    {
        return $this->canonicalPhone($this->customerPhone) === $this->canonicalPhone($this->userPhone($customer))
            && mb_strtolower(trim($this->customerEmail)) === mb_strtolower(trim((string) ($customer->email ?? '')))
            && trim($this->customerFirstName) === trim((string) ($customer->fl_name ?? ''))
            && trim($this->customerLastName) === trim((string) ($customer->last_name ?? ''));
    }

    private function userPhone(MainUser $user): string
    {
        return $this->normalizePhone((string) ($user->mobile ?? $user->phone ?? ''));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }

    private function canonicalPhone(string $phone): string
    {
        return substr($this->normalizePhone($phone), -10);
    }

    private function maskPhone(string $phone): string
    {
        return $phone === '' ? '—' : str_repeat('*', max(0, strlen($phone) - 4)).substr($phone, -4);
    }

    private function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return '—';
        }
        [$name, $domain] = explode('@', $email, 2);

        return mb_substr($name, 0, 1).str_repeat('*', max(1, mb_strlen($name) - 1)).'@'.$domain;
    }

    private function findVisibleReferral(int $referralId): Referral
    {
        return app(ReferralAccessService::class)
            ->visibleQuery($this->user())
            ->findOrFail($referralId);
    }

    private function user(): MainUser
    {
        $user = Auth::guard('mainUsers')->user();
        abort_unless($user instanceof MainUser, 401);

        return $user;
    }

    private function parseAccountValue(string $value): array
    {
        if ($value === 'personal') {
            return ['personal', $this->user()->getKey()];
        }

        [$type, $id] = array_pad(explode(':', $value, 2), 2, null);
        abort_unless($id !== null && ctype_digit($id), 422);

        return [$type, (int) $id];
    }

    private function newReferralNumber(): string
    {
        do {
            $number = 'REF-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Referral::query()->where('referral_number', $number)->exists());

        return $number;
    }

    private function resetReferralForm(): void
    {
        $this->reset([
            'receiverSearch',
            'destination',
            'selectedDestinationName',
            'selectedDestinationDetails',
            'showDestinationDropdown',
            'customerFirstName',
            'customerLastName',
            'customerPhone',
            'customerEmail',
            'customerUserId',
            'serviceId',
            'note',
        ]);
        $this->referringAccount = 'personal';
    }

    private function resetActionState(): void
    {
        $this->showConfirmation = false;
        $this->pendingAction = null;
        $this->pendingReferralId = null;
    }
}
