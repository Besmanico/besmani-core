<?php

namespace App\Services\Referrals;

use App\Models\InfoActivity;
use App\Models\MainUser;
use App\Models\Referral;
use Illuminate\Database\Eloquent\Builder;

class ReferralAccessService
{
    public function isProvider(MainUser $user): bool
    {
        return (int) $user->service_pr === 1;
    }

  public function visibleQuery(MainUser $user)
{
    $businessIds = $this->authorizedBusinessIds($user);

    return Referral::query()
        ->where(function ($query) use ($user, $businessIds): void {

            // Referrals sent by this user
            $query->where('referrer_user_id', $user->getKey())

                // Referrals received directly by this provider
                ->orWhere('receiver_user_id', $user->getKey())

                // Referrals where this user is the customer/patient
                ->orWhere('customer_user_id', $user->getKey());

            // Referrals received by one of the user's businesses
            if ($businessIds !== []) {
                $query->orWhereIn('receiver_business_id', $businessIds);
            }
        });
}

    public function incomingQuery(MainUser $user): Builder
    {
        $businessIds = $this->authorizedBusinessIds($user);

        return Referral::query()->where(function (Builder $query) use ($user, $businessIds): void {
            $query->where('receiver_user_id', $user->getKey());

            if ($businessIds !== []) {
                $query->orWhereIn('receiver_business_id', $businessIds);
            }
        });
    }

    public function outgoingQuery(MainUser $user): Builder
    {
        $businessIds = $this->authorizedBusinessIds($user);

        return Referral::query()->where(function (Builder $query) use ($user, $businessIds): void {
            $query->where('referrer_user_id', $user->getKey());

            if ($businessIds !== []) {
                $query->orWhereIn('referrer_business_id', $businessIds);
            }
        });
    }

    public function authorizedBusinessIds(MainUser $user): array
    {
        return InfoActivity::query()
            ->where('user_id', $user->getKey())
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->all();
    }

    public function ownsBusiness(MainUser $user, ?int $businessId): bool
    {
        return $businessId !== null && in_array($businessId, $this->authorizedBusinessIds($user), true);
    }
}
