<?php

namespace App\Policies;

use App\Models\MainUser;
use App\Models\Referral;
use App\Services\Referrals\ReferralAccessService;

class ReferralPolicy
{
    public function __construct(private readonly ReferralAccessService $access) {}

    public function viewAny(MainUser $user): bool
    {
        return true;
    }

    
    public function view(MainUser $user, Referral $referral): bool
{
    // Customer / Patient can always view their own referral
    if ((int) $referral->customer_user_id === (int) $user->getKey()) {
        return true;
    }

    // Normal user can view referrals they created
    if (! $this->access->isProvider($user)) {
        return (int) $referral->referrer_user_id === (int) $user->getKey();
    }

    // Provider access
    return (int) $referral->referrer_user_id === (int) $user->getKey()
        || (int) $referral->receiver_user_id === (int) $user->getKey()
        || $this->access->ownsBusiness($user, $referral->referrer_business_id)
        || $this->access->ownsBusiness($user, $referral->receiver_business_id);
}

    public function create(MainUser $user): bool
    {
        return true;
    }

    public function accept(MainUser $user, Referral $referral): bool
    {
        return $this->access->isProvider($user)
            && $referral->status === 'pending'
            && $this->isReceiver($user, $referral);
    }

    public function complete(MainUser $user, Referral $referral): bool
    {
        return $this->access->isProvider($user)
            && $referral->status === 'accepted'
            && $this->isReceiver($user, $referral);
    }

    public function cancel(MainUser $user, Referral $referral): bool
    {
        return $referral->status === 'pending' && $this->isReferrer($user, $referral);
    }

    private function isReceiver(MainUser $user, Referral $referral): bool
    {
        return (int) $referral->receiver_user_id === (int) $user->getKey()
            || $this->access->ownsBusiness($user, $referral->receiver_business_id);
    }

    private function isReferrer(MainUser $user, Referral $referral): bool
    {
        return (int) $referral->referrer_user_id === (int) $user->getKey()
            || $this->access->ownsBusiness($user, $referral->referrer_business_id);
    }
}
