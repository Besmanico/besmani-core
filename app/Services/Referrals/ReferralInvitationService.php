<?php

namespace App\Services\Referrals;

use App\Mail\ProviderSendMail;
use App\Models\MainUser;
use App\Models\ReferralInvitation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class ReferralInvitationService
{
    public function send(MainUser $inviter, string $recipient, string $party): ReferralInvitation
    {
        $channel = filter_var($recipient, FILTER_VALIDATE_EMAIL) ? 'email' : 'sms';
        $inviterName = trim(($inviter->fl_name ?? '').' '.($inviter->last_name ?? '')) ?: config('app.name');
        $url = url('/');
        $message = "{$inviterName} invited you to join ".config('app.name')." as a {$party}. Register here: {$url}";

        $invitation = ReferralInvitation::query()->create([
            'invited_by_user_id' => $inviter->getKey(),
            'recipient' => $recipient,
            'channel' => $channel,
            'party' => $party,
            'inviter_name' => $inviterName,
            'message' => $message,
        ]);

        try {
            if ($channel === 'email') {
                Mail::to($recipient)->send(new ProviderSendMail($inviterName, $message));
            } else {
                $this->sendSms($recipient, $message);
            }

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

    private function sendSms(string $phone, string $message): void
    {
        $endpoint = config('services.referral_sms.endpoint');
        if (! $endpoint) {
            throw new RuntimeException('The referral SMS gateway is not configured.');
        }

        Http::withToken((string) config('services.referral_sms.token'))
            ->timeout(10)
            ->post($endpoint, ['to' => $phone, 'message' => $message])
            ->throw();
    }
}
