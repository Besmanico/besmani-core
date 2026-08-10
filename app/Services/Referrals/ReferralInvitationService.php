<?php

namespace App\Services\Referrals;

use App\Models\MainUser;
use App\Models\ReferralInvitation;
use App\Services\Notifications\NotificationService;
use Illuminate\Support\Str;
use Throwable;

class ReferralInvitationService
{
    public function __construct(private readonly NotificationService $notifications) {}

    public function create(MainUser $inviter, string $recipient, string $party, string $channel = 'copy', ?int $businessId = null): ReferralInvitation
    {
        $token = Str::random(64);
        $inviterName = trim(($inviter->fl_name ?? '').' '.($inviter->last_name ?? '')) ?: config('app.name');
        $url = route('referral-invitations.accept', ['token' => $token]);
        $message = "{$inviterName} invited you to join Besmani".($party === 'provider' ? ' and connect your business for referrals.' : ' and connect with trusted Providers.')." {$url}";

        $invitation = ReferralInvitation::query()->create([
            'invited_by_user_id' => $inviter->getKey(),
            'inviter_business_id' => $businessId,
            'recipient' => $recipient,
            'recipient_email' => filter_var($recipient, FILTER_VALIDATE_EMAIL) ? mb_strtolower($recipient) : null,
            'recipient_phone' => filter_var($recipient, FILTER_VALIDATE_EMAIL) ? null : preg_replace('/\D+/', '', $recipient),
            'channel' => $channel,
            'party' => $party,
            'token_hash' => hash('sha256', $token),
            'inviter_name' => $inviterName,
            'message' => $message,
            'expires_at' => now()->addDays((int) config('referrals.invitation_expiry_days', 30)),
        ]);

        $invitation->setAttribute('invitation_url', $url);

        return $invitation;
    }

    public function send(MainUser $inviter, string $recipient, string $party, ?string $channel = null, ?int $businessId = null): ReferralInvitation
    {
        $channel ??= filter_var($recipient, FILTER_VALIDATE_EMAIL) ? 'email' : 'sms';
        $invitation = $this->create($inviter, $recipient, $party, $channel, $businessId);

        try {
            $this->notifications->sendInvitation($channel, $recipient, $party, $invitation->message);
            $invitation->update(['status' => 'sent', 'sent_at' => now()]);
        } catch (Throwable $exception) {
            $invitation->update([
                'status' => 'failed',
                'failure_reason' => mb_substr($exception->getMessage(), 0, 2000),
            ]);
            throw $exception;
        }

        return $invitation;
    }
}
