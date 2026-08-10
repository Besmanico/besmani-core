<?php

namespace App\Services\Referrals;

use App\Models\MainUser;
use App\Models\Referral;
use App\Models\ReferralStatusHistory;
use App\Models\TokenLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReferralWorkflowService
{
    public function accept(Referral $referral, MainUser $actor): Referral
    {
        return $this->transition($referral, $actor, 'accepted');
    }

    public function cancel(Referral $referral, MainUser $actor): Referral
    {
        return $this->transition($referral, $actor, 'cancelled');
    }

    public function complete(Referral $referral, MainUser $actor): Referral
    {
        return DB::transaction(function () use ($referral, $actor): Referral {
            $referral = Referral::query()->lockForUpdate()->findOrFail($referral->getKey());
            $oldStatus = $referral->status;
            if ($oldStatus !== 'accepted') {
                throw ValidationException::withMessages([
                    'status' => 'Only an accepted referral can be completed.',
                ]);
            }
            $coinAward = (int) $referral->referral_reward_bc;

            $referral->update([
                'status' => 'completed',
                'token_amount' => $coinAward,
                'completed_at' => now(),
            ]);

            $this->recordStatusChange($referral, $actor, $oldStatus, 'completed');

            TokenLedger::query()->firstOrCreate(
                [
                    'referral_id' => $referral->getKey(),
                    'transaction_type' => 'earned',
                ],
                [
                    'from_user_id' => null,
                    'from_business_id' => $referral->receiver_business_id,
                    'to_user_id' => $referral->referrer_user_id,
                    'to_business_id' => $referral->referrer_business_id,
                    'token_amount' => $coinAward,
                    'status' => 'completed',
                    'description' => 'Besmani COIN award for a completed referral.',
                    'created_by' => $actor->getKey(),
                ],
            );

            return $referral->refresh();
        });
    }

    private function transition(Referral $referral, MainUser $actor, string $newStatus): Referral
    {
        return DB::transaction(function () use ($referral, $actor, $newStatus): Referral {
            $referral = Referral::query()->lockForUpdate()->findOrFail($referral->getKey());
            $oldStatus = $referral->status;
            if ($oldStatus !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Only a pending referral can be changed by this action.',
                ]);
            }
            $attributes = ['status' => $newStatus];

            if ($newStatus === 'accepted') {
                $attributes['accepted_at'] = now();
            } elseif ($newStatus === 'cancelled') {
                $attributes['cancelled_at'] = now();
            }

            $referral->update($attributes);
            $this->recordStatusChange($referral, $actor, $oldStatus, $newStatus);

            return $referral->refresh();
        });
    }

    private function recordStatusChange(
        Referral $referral,
        MainUser $actor,
        string $oldStatus,
        string $newStatus,
    ): void {
        ReferralStatusHistory::query()->create([
            'referral_id' => $referral->getKey(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by_user_id' => $actor->getKey(),
        ]);
    }
}
