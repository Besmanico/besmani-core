<?php

namespace App\Observers;

use App\Models\MainUser;
use App\Models\ReferralInvitation;

class ReferralInvitationAcceptanceObserver
{
    public function created(MainUser $user): void
    {
        if (app()->runningInConsole() || ! session()->has('referral_invitation_id')) {
            return;
        }

        $invitation = ReferralInvitation::query()->find(session()->pull('referral_invitation_id'));
        if (! $invitation || ($invitation->expires_at && $invitation->expires_at->isPast())) {
            return;
        }

        $emailMatches = $invitation->recipient_email
            && mb_strtolower((string) $user->email) === mb_strtolower($invitation->recipient_email);
        $phoneMatches = $invitation->recipient_phone
            && substr(preg_replace('/\D+/', '', (string) $user->mobile), -10) === substr($invitation->recipient_phone, -10);

        if ($emailMatches || $phoneMatches || ($invitation->recipient_email === null && $invitation->recipient_phone === null)) {
            $invitation->update(['status' => 'accepted', 'accepted_at' => now()]);
        }
    }
}
