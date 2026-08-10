<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationProvider;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CustomerIoNotificationProvider implements NotificationProvider
{
    public function send(string $channel, string $recipient, string $subject, string $message): void
    {
        $appKey = (string) config('services.customerio.app_api_key');
        $transactionalId = $channel === 'email'
            ? config('services.customerio.invitation_email_transactional_id')
            : config('services.customerio.invitation_sms_transactional_id');

        if ($appKey === '' || blank($transactionalId)) {
            throw new RuntimeException('Customer.io invitation delivery is not configured. Copy or share the invitation link instead.');
        }

        Http::withToken($appKey)
            ->acceptJson()
            ->timeout(10)
            ->post(rtrim((string) config('services.customerio.transactional_endpoint'), '/').'/'.$transactionalId.'/send', [
                'to' => $recipient,
                'identifiers' => [$channel => $recipient],
                'message_data' => ['subject' => $subject, 'message' => $message],
            ])->throw();
    }
}
