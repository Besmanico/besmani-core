<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationProvider;

class NotificationService
{
    public function __construct(private readonly NotificationProvider $provider) {}

    public function sendInvitation(string $channel, string $recipient, string $party, string $message): void
    {
        $subject = $party === 'provider' ? "You're invited to join Besmani" : "You're invited to Besmani";
        $this->provider->send($channel, $recipient, $subject, $message);
    }
}
