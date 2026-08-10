<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfoActivity;
use App\Models\MainUser;
use App\Models\Referral;
use App\Models\ReferralStatusHistory;
use App\Models\TokenLedger;
use App\Services\Referrals\ReferralAccessService;
use App\Services\Referrals\ReferralServiceCatalog;
use App\Services\Referrals\ReferralWorkflowService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReferralController extends Controller
{
    public function __construct(
        private readonly ReferralAccessService $access,
        private readonly ReferralServiceCatalog $catalog,
        private readonly ReferralWorkflowService $workflow,
    ) {}

    public function bootstrap(Request $request): JsonResponse
    {
        $user = $this->user($request);
        $isProvider = $this->access->isProvider($user);
        $allowedSections = $isProvider ? ['dashboard', 'incoming', 'outgoing'] : ['dashboard'];
        $validated = $request->validate([
            'section' => ['sometimes', 'string', Rule::in($allowedSections)],
        ]);
        $section = $validated['section'] ?? 'dashboard';
        $businessIds = $isProvider ? $this->access->authorizedBusinessIds($user) : [];
        $visible = $this->access->visibleQuery($user);
        $incoming = $isProvider ? $this->access->incomingQuery($user) : Referral::query()->whereRaw('1 = 0');
        $outgoing = $this->access->outgoingQuery($user);
        $selected = match ($section) {
            'incoming' => clone $incoming,
            'outgoing' => clone $outgoing,
            default => clone $visible,
        };

        $balance = TokenLedger::query()
            ->where('status', 'completed')
            ->where(function (Builder $query) use ($user, $businessIds): void {
                $query->where('to_user_id', $user->getKey());
                if ($businessIds !== []) {
                    $query->orWhereIn('to_business_id', $businessIds);
                }
            })->sum('token_amount');

        $businesses = $isProvider
            ? InfoActivity::query()->whereIn('id', $businessIds)->orderBy('name')->get()
            : collect();

        return response()->json(['data' => [
            'section' => $section,
            'is_provider' => $isProvider,
            'coin_balance' => (int) $balance,
            'counts' => [
                'all' => (clone $visible)->count(),
                'incoming' => $isProvider ? (clone $incoming)->count() : 0,
                'outgoing' => (clone $outgoing)->count(),
                'pending' => (clone $visible)->where('status', 'pending')->count(),
                'completed' => (clone $visible)->where('status', 'completed')->count(),
            ],
            'referring_accounts' => collect([[
                'value' => 'personal',
                'type' => 'personal',
                'id' => (int) $user->getKey(),
                'name' => trim((string) $user->fl_name.' '.(string) $user->last_name),
            ]])->concat($businesses->map(fn (InfoActivity $business): array => [
                'value' => 'business:'.$business->getKey(),
                'type' => 'business',
                'id' => (int) $business->getKey(),
                'name' => (string) $business->name,
            ]))->values(),
            'referrals' => $selected->with(['referrerUser', 'referrerBusiness', 'receiverUser', 'receiverBusiness'])
                ->latest()->limit(50)->get()->map(fn (Referral $referral): array => $this->referralData($referral))->values(),
        ]]);
    }

    public function destinations(Request $request): JsonResponse
    {
        $this->user($request);
        $validated = $request->validate(['query' => ['required', 'string', 'min:2', 'max:100']]);
        $term = trim($validated['query']);
        $phone = $this->normalizePhone($term);
        if (preg_match('/^[\d\s()+-]+$/', $term) === 1 && strlen($phone) < 10) {
            return response()->json(['data' => []]);
        }

        $like = '%'.$term.'%';
        $providerIds = MainUser::query()->where(function (Builder $query) use ($like, $phone): void {
            $query->where('fl_name', 'like', $like)->orWhere('last_name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhereRaw("CONCAT(COALESCE(fl_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", [$like]);
            if ($phone !== '') {
                $query->orWhereRaw($this->phoneSql('mobile'), [$this->canonicalPhone($phone)]);
            }
        })->pluck('id');

        $businesses = InfoActivity::query()
            ->whereNotNull('name')->where('name', '<>', '')
            ->where(function (Builder $query) use ($like, $phone, $providerIds): void {
                $query->where('name', 'like', $like)->orWhere('email', 'like', $like)
                    ->orWhere('city', 'like', $like)->orWhere('province', 'like', $like)
                    ->orWhereIn('user_id', $providerIds);
                if ($phone !== '') {
                    $query->orWhereRaw($this->phoneSql('phone'), [$this->canonicalPhone($phone)]);
                }
            })->orderBy('name')->limit(20)->get();
        $providers = MainUser::query()->whereIn('id', $businesses->pluck('user_id')->filter()->unique())->get()->keyBy('id');

        return response()->json(['data' => $businesses->map(function (InfoActivity $business) use ($providers): array {
            $provider = $providers->get($business->user_id);

            return [
                'id' => (int) $business->getKey(),
                'value' => 'business:'.$business->getKey(),
                'name' => (string) $business->name,
                'provider_name' => trim((string) ($provider?->fl_name ?? '').' '.(string) ($provider?->last_name ?? '')),
                'city' => $business->city,
                'province' => $business->province,
            ];
        })->values()]);
    }

    public function services(Request $request, int $business): JsonResponse
    {
        $this->user($request);
        $destination = InfoActivity::query()->findOrFail($business);

        return response()->json(['data' => $this->catalog->forBusiness($destination)]);
    }

    public function customerByPhone(Request $request): JsonResponse
    {
        $this->user($request);
        $validated = $request->validate(['phone' => ['required', 'string', 'max:30']]);
        $canonical = $this->canonicalPhone($validated['phone']);
        if (strlen($canonical) < 10) {
            throw ValidationException::withMessages(['phone' => 'Enter a valid phone number.']);
        }
        $customer = MainUser::query()->whereRaw($this->phoneSql('mobile'), [$canonical])->first();

        return response()->json(['data' => $customer ? [
            'id' => (int) $customer->getKey(),
            'first_name' => (string) ($customer->fl_name ?? ''),
            'last_name' => (string) ($customer->last_name ?? ''),
            'phone' => $this->normalizePhone((string) ($customer->mobile ?? '')),
            'email' => (string) ($customer->email ?? ''),
        ] : null]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->user($request);
        $this->authorize('create', Referral::class);
        $validated = $request->validate([
            'referring_account' => ['sometimes', 'string', 'max:100'],
            'destination_business_id' => ['required', 'integer'],
            'customer_user_id' => ['required', 'integer'],
            'service_key' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);
        $business = InfoActivity::query()->find($validated['destination_business_id']);
        if (! $business || ! MainUser::query()->whereKey($business->user_id)->exists()) {
            throw ValidationException::withMessages(['destination_business_id' => 'Select an existing registered Besmani Business.']);
        }
        $customer = MainUser::query()->find($validated['customer_user_id']);
        if (! $customer) {
            throw ValidationException::withMessages(['customer_user_id' => 'Select an existing registered Besmani User.']);
        }
        $service = $this->catalog->findForBusiness($business, $validated['service_key'] ?? null);
        if (! empty($validated['service_key']) && $service === null) {
            throw ValidationException::withMessages(['service_key' => 'The selected service is not available for this destination.']);
        }
        $referrerBusinessId = $this->referrerBusinessId($user, $validated['referring_account'] ?? 'personal');

        $referral = DB::transaction(function () use ($user, $business, $customer, $service, $referrerBusinessId, $validated): Referral {
            $referral = Referral::query()->create([
                'referral_number' => $this->newReferralNumber(),
                'referrer_user_id' => $user->getKey(),
                'referrer_business_id' => $referrerBusinessId,
                'receiver_user_id' => null,
                'receiver_business_id' => $business->getKey(),
                'customer_user_id' => $customer->getKey(),
                'customer_first_name' => $customer->fl_name,
                'customer_last_name' => $customer->last_name,
                'customer_phone' => $this->normalizePhone((string) ($customer->mobile ?? '')),
                'customer_email' => $customer->email ?: null,
                'service_id' => $service['id'] ?? null,
                'service_type' => $service['type'] ?? null,
                'service_title' => $service['title'] ?? null,
                'reward_type' => 'besmani_coin',
                'token_amount' => 0,
                'note' => $validated['note'] ?? null,
                'status' => 'pending',
            ]);
            ReferralStatusHistory::query()->create([
                'referral_id' => $referral->getKey(), 'old_status' => null,
                'new_status' => 'pending', 'changed_by_user_id' => $user->getKey(),
            ]);

            return $referral;
        });

        return response()->json(['data' => $this->referralData($referral->load(['referrerUser', 'referrerBusiness', 'receiverBusiness']))], 201);
    }

    public function show(Request $request, Referral $referral): JsonResponse
    {
        $this->authorize('view', $referral);

        return response()->json(['data' => $this->referralData($referral->load([
            'referrerUser', 'referrerBusiness', 'receiverUser', 'receiverBusiness', 'statusHistories',
        ]), true)]);
    }

    public function action(Request $request, Referral $referral, string $action): JsonResponse
    {
        $user = $this->user($request);
        if (! in_array($action, ['accept', 'complete', 'cancel'], true)) {
            abort(404);
        }
        $this->authorize($action, $referral);
        $referral = match ($action) {
            'accept' => $this->workflow->accept($referral, $user),
            'complete' => $this->workflow->complete($referral, $user),
            'cancel' => $this->workflow->cancel($referral, $user),
        };

        return response()->json(['data' => $this->referralData($referral->load(['referrerUser', 'referrerBusiness', 'receiverBusiness']))]);
    }

    private function referralData(Referral $referral, bool $detailed = false): array
    {
        $data = [
            'id' => (int) $referral->getKey(), 'number' => $referral->referral_number,
            'status' => $referral->status, 'customer' => [
                'id' => $referral->customer_user_id ? (int) $referral->customer_user_id : null,
                'first_name' => $referral->customer_first_name, 'last_name' => $referral->customer_last_name,
                'phone' => $referral->customer_phone, 'email' => $referral->customer_email,
            ],
            'service' => $referral->service_id ? [
                'key' => $referral->service_type.':'.$referral->service_id,
                'type' => $referral->service_type, 'id' => (int) $referral->service_id, 'title' => $referral->service_title,
                'bc' => $referral->bc,
            ] : null,
            'referrer' => $this->partyData($referral, 'referrer'),
            'receiver' => $this->partyData($referral, 'receiver'),
            'token_amount' => (int) $referral->token_amount,
            'note' => $referral->note, 'created_at' => $referral->created_at?->toISOString(),
            'accepted_at' => $referral->accepted_at?->toISOString(), 'completed_at' => $referral->completed_at?->toISOString(),
        ];
        if ($detailed) {
            $data['history'] = $referral->statusHistories->map(fn ($history): array => [
                'from' => $history->old_status, 'to' => $history->new_status,
                'changed_by_user_id' => $history->changed_by_user_id ? (int) $history->changed_by_user_id : null,
                'note' => $history->note, 'created_at' => $history->created_at?->toISOString(),
            ])->values();
        }

        return $data;
    }

    private function partyData(Referral $referral, string $side): array
    {
        $business = $referral->getRelationValue($side.'Business');
        $user = $referral->getRelationValue($side.'User');

        return $business ? ['type' => 'business', 'id' => (int) $business->getKey(), 'name' => (string) $business->name]
            : ['type' => 'personal', 'id' => $user ? (int) $user->getKey() : null,
                'name' => $user ? trim((string) $user->fl_name.' '.(string) $user->last_name) : null];
    }

    private function referrerBusinessId(MainUser $user, string $account): ?int
    {
        if ($account === 'personal') {
            return null;
        }
        if (! preg_match('/^business:(\d+)$/', $account, $matches)) {
            throw ValidationException::withMessages(['referring_account' => 'The referring account is invalid.']);
        }
        $id = (int) $matches[1];
        if (! $this->access->isProvider($user) || ! $this->access->ownsBusiness($user, $id)) {
            abort(403);
        }

        return $id;
    }

    private function user(Request $request): MainUser
    {
        $user = $request->user();
        abort_unless($user instanceof MainUser, 401);

        return $user;
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }

    private function canonicalPhone(string $phone): string
    {
        return substr($this->normalizePhone($phone), -10);
    }

    private function phoneSql(string $column): string
    {
        return "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE({$column}, ' ', ''), '-', ''), '(', ''), ')', ''), '+', ''), 10) = ?";
    }

    private function newReferralNumber(): string
    {
        do {
            $number = 'REF-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Referral::query()->where('referral_number', $number)->exists());

        return $number;
    }
}
